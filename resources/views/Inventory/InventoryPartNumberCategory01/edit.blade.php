@extends('layouts.app'

)@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar tipo de material 01
                </div>
                <div class="card-body">
                    <form method="POST"
                          action="{{url('inventorypartnumbercategory01/' . $inventorypartnumbercategory01->id)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventorypartnumbercategory01.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Inventory.InventoryPartNumberCategory01.partials.formEdit')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

