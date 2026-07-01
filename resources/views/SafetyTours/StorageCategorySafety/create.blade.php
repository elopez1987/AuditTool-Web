@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar nueva categoría de seguridad
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="POST" action="{{url('storage-category-safeties')}}" enctype="multipart/form-data">
                                @csrf
                                <hr class="b-3">
                                <div class="row">
                                    <x-select2
                                            name="centerid"
                                            label="Centros de distribución"
                                            :options="$centers"
                                            valueField="centerid"
                                            textField="name"
                                            :required="true"
                                    />
                                    <x-text-input
                                            name="name"
                                            label="Nombre del área"
                                            required
                                            uppercase
                                    />
                                </div>
                                <hr class="b-3">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="{{route('storage-category-safeties.create')}}">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('storage-category-safeties.index')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   @include('layouts.Partials.Errors')
@endsection

@section('css')
@endsection


@section('script')

@endsection

