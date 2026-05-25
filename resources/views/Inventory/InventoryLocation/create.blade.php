@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    AGREGAR REGISTRO
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('inventorycards')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('inventorycards.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventorycards.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Inventory.InventoryCard.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

