@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Inventario Fiscal
                </div>
                <div class="card-body">



                    <div class="row">
                        <div class="col-lg-12">
                            <form method="GET" id="search" role="form">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="">
                                            @include('buttons.search')
                                        </a>
                                        <a>
                                            @include('buttons.reloadajax')
                                        </a>
                                        <a href="{{url('inventoryheads')}}">
                                            @include('buttons.cancel')
                                        </a>
                                    </div>
                                </div>
                                <hr class="m-t-3">
                                <div class="row">
                                    <x-text-input
                                            name="nameinventory"
                                            label="Nombre del inventario"
                                            uppercase
                                            readonly
                                            value="{{$inventoryHead->name}}"
                                    />
                                    <x-text-input
                                            name="name"
                                            label="Nombre del pasillo"
                                            uppercase
                                    />
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <table width="100%" id="table-cards" class="table table-bordered  table-condensed">
                                <thead>
                                <tr>
                                    <th>ID Inventario</th>
                                    <th>Pasillo</th>
                                    <th>Cédula</th>
                                    <th>Contador Asingado Para Conteo 01</th>
                                    <th>Contador Asingado Para Conteo 02</th>
                                    <th>Estatus Conteo 01</th>
                                    <th>Estatus Conteo 02</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    {{-- Variables dinámicas de Blade expuestas al JS externo --}}
    <script>
        var CardDetail = {
            ajaxUrl:   '{{ route("getCardsInventory", $inventoryHead->id) }}',
            deleteUrl: '{{ url("deleteCardDetail") }}',
            csrfToken: '{{ csrf_token() }}'
        };
    </script>

    <script src="{{ asset('js/inventory/card-detail.js') }}"></script>

@endsection
