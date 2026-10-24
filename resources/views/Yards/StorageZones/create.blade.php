@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar Zona
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storagezones')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                @can('storagezones.create')
                                    <a href="{{route('storagezones.create')}}">
                                        @include('buttons.save')
                                    </a>
                                @endcan
                                <a href="{{route('storagezones.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Yards.StorageZones.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')
@endsection

