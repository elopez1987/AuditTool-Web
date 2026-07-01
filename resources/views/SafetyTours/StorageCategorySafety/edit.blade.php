@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storage-category-safeties/' . $storage_category_safety->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storage-category-safeties.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <x-text-input
                                    name="name"
                                    label="Nombre del área"
                                    required
                                    uppercase
                                    value="{{ $storage_category_safety->name }}"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

