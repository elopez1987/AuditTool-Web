@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Crear inventario
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('inventoryhead')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('inventoryhead.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventoryhead.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        @include('Counting.InventoryHead.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')

@endsection

@section('script')

@endsection

