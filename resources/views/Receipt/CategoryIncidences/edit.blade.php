@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar registro
                </div>
                <div class="card-body">
                    <form method="POST"
                          action="{{url('storagecategoryincidences/' . $storagecategoryincidence->categoryincid)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagecategoryincidences.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Receipt.CategoryIncidences.partials.formEdit')
                    </form>
                </div>
                <div class="row">
                    @include('layouts.Partials.Errors')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

