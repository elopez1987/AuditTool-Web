@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de ubicaciones
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="POST" action="{{url('create-location/' . $inventorycard->id)}}">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <a href="">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('inventorycards.index')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mb-3">
                                <div class="row">
                                    <x-text-input
                                            name="name"
                                            label="Nombre de la ubicación"
                                            required
                                            uppercase
                                    />
                                    <x-select2
                                            name="inventoryunitsmeasure_id"
                                            label="Unidad de medida para conteo"
                                            :options="$unitmeasures"
                                            text-field="description"
                                            required
                                    />
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr class="mb-3 mt-3">
                    <div class="row">
                        <div class="col-lg-12">
                                <table class="table table-bordered table-condensed ">
                                    <thead>
                                        <tr>
                                            <th>Ubicación</th>
                                            <th>Unidad de medida de conteo</th>
                                            <th>Auxiliar 01</th>
                                            <th>Auxiliar 02</th>
                                            <th>Auxiliar 03</th>
                                            <th>Estatus</th>
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

@endsection

