@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Asignar 3er conteo a usuario
                </div>
                <div class="card-body">
                    {{--                    <form method="POST" action="{{url('inventoryheads/' . $inventoryhead->id)}}">--}}
                    {{--                        {{ method_field('PUT') }}--}}
                    {{--                        @csrf--}}
                    <div class="row">
                        <div class="col-12">
                            <a href="{{route('inventoryheads.index')}}">
                                @include('buttons.back')
                            </a>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="row">

                    </div>
                    {{--                    </form>--}}
                </div>
            </div>
        </div>
    </div>
    <hr class="mb-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Asignación
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <table class="table table-hover table-condensed table-bordered tablegeneral">
                                <thead>
                                <tr>
                                    <th>Cédula</th>
                                    <th>Cantidad de errores</th>
                                    <th>Usuario asignado</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($errors as $error)
                                    <tr>
                                        <td>{{$error->cardid}}</td>
                                        <td>{{$error->total_errors}}</td>
                                        <td>
                                            <a href="" data-target="#modal-{{$error->}}"
                                               data-toggle="modal" data-placement="top">
                                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                                        data-toggle="tooltip" data-placement="top" title="Cerrar Inventario">
                                                    <img src="{{asset('/ico/adreload.png')}}" alt="" class="img-16">
                                                    <span style="font-size: 11px;"> Reiniciar Conteo 1</span>
                                                </button>
                                            </a>
                                            @include('Inventory.InventoryHead.LocationCard.initialize-count1')
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')

@endsection

@section('script')
    @include('layouts.Partials.DataTable')
@endsection

