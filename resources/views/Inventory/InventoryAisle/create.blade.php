@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                  AGREGAR NUEVA AREA
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('inventoryaisles')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('inventoryaisles.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventoryaisles.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Inventory.InventoryAisle.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

