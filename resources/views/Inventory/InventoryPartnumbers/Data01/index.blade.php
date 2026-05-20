@extends('layouts.app'

)@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar material
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('inventorypartnumbersadddata01/' . $inventoryPartNumber->id)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventorypartnumbers.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">

                        <div class="row">
                            <x-text-input
                                    name="prtnum"
                                    label="Número de material"
                                    uppercase
                                    disabled
                                    value="{{$inventoryPartNumber->prtnum}}"
                            />
                            <x-text-input
                                    name="lngdsc"
                                    label="Descripción"
                                    uppercase
                                    disabled
                                    value="{{$inventoryPartNumber->lngdsc}}"
                            />
                        </div>

                        <div class="row">
                            <x-text-input
                                    name="container_type"
                                    label="Container Type"
                                    uppercase
                                    value="{{$inventoryPartNumberData01->container_type ?? ''}}"
                            />
                            <x-text-input
                                    name="pn_turbo"
                                    label="PN Turbo"
                                    uppercase
                                    value="{{$inventoryPartNumberData01->pn_turbo ?? ''}}"
                            />
                        </div>
                        <div class="row">
                            <x-text-input
                                    name="quantity"
                                    label="Quantity"
                                    type="number"
                                    value="{{$inventoryPartNumberData01->quantity ?? ''}}"
                            />
                            <x-text-input
                                    name="machines_component"
                                    label="Machines per component"
                                    type="number"
                                    value="{{$inventoryPartNumberData01->machines_component ?? ''}}"
                            />
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

