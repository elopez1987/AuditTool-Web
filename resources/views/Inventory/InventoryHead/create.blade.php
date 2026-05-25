@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                  AGREGAR INVENTARUO
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('inventoryheads')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('inventoryheads.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventoryheads.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Inventory.InventoryHead.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

