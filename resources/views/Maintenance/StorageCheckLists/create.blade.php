@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar check list
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storagechecklistlifttrucks')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('storagechecklistlifttrucks.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagechecklistlifttrucks.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Maintenance.StorageCheckLists.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

