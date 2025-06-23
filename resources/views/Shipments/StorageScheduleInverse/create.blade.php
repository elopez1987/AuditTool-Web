@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar embarque
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storagescheduleinverse')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('storagescheduleinverse.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagescheduleinverse.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-1">
                        @include('Shipments.StorageScheduleInverse.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

