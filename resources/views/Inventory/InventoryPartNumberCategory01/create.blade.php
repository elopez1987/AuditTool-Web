@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    agrega tipo de material
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('inventorypartnumbercategory01')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('inventorypartnumbercategory01.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventorypartnumbercategory01.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Inventory.InventoryPartNumberCategory01.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

