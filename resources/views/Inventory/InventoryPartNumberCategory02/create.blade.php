@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    agregar tipo de material 02
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('inventorypartnumbercategory02')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('inventorypartnumbercategory02.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventorypartnumbercategory02.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Inventory.InventoryPartNumberCategory02.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

