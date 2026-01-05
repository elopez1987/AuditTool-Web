@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    agregar categoria de inventario
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('inventorystockcategories')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('inventorystockcategories.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventorystockcategories.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Inventory.InventoryStockCategory.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

