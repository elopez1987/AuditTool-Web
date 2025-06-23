@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Crear inventario
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('inventorybox')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('inventorybox.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventorybox.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        @include('Yards.InventoryBoxes.partials.formCreate')
                    </form>
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

