@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar Estatus
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storageunitstatus')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('storageunitstatus.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storageunitstatus.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Yards.StorageUnitStatus.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

