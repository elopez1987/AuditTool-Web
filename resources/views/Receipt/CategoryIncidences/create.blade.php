@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar Categoria de incidencia
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storagecategoryincidences')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('storagecategoryincidences.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagecategoryincidences.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Shipments.CategoryIncidences.partials.formCreate')
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

