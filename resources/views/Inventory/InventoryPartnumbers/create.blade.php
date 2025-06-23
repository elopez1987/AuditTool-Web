@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar material
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('inventorypartnumbers')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('inventorypartnumbers.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventorypartnumbers.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Inventory.InventoryPartnumbers.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

