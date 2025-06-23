@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    agrega unidad de medida.
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('inventoryunitmeasure')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('inventoryunitmeasure.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventoryunitmeasure.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Inventory.InventoryUnits.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

