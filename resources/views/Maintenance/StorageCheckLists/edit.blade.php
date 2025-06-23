@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar check list
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storagechecklistlifttrucks/' . $checkList->id)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagechecklistlifttrucks.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Maintenance.StorageCheckLists.partials.formEdit')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

