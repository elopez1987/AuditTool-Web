@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar Categoria
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('categories-incidents')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('categories-incidents.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('categories-incidents.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Receipt.CategoryIncidences.partials.formCreate')
                    </form>
                </div>

            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

