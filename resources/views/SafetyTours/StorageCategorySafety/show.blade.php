@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <a href="{{route('storage-category-safeties.index')}}">
                        <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                            <img src="{{asset('/ico/adback.png')}}" alt="" class="img-16 ml-1">
                            <span class="text-button"> Regresar</span>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de la incidencia.
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card ">
                                <div class="card-body">
                                    <div class="row">
                                        Categoria : {{$storage_category_safety->id}} - {{$storage_category_safety->name}}
                                    </div>
                                    <div class="row">
                                        <x-select2
                                                name="storage_safety_responsible_area_id"
                                                label="Centros de distribución"
                                                :options="$responsible"
                                                valueField="id"
                                                textField="name"
                                                :required="true"
                                                colClass="col-lg-2 col-md-12"
                                        />
                                        <x-text-input
                                                name="name"
                                                label="Nombre de la subcategoría"
                                                required
                                                uppercase
                                                colClass="col-lg-2 col-md-12"
                                        />
                                    </div>
                                    <div class="row">
                                        <x-text-input
                                                name="level01"
                                                label="Nivel 1"
                                                colClass="col-lg-2 col-md-12"
                                        />
                                        <x-text-input
                                                name="timeout01"
                                                label="Tiempo de espera"
                                                colClass="col-lg-2 col-md-12"
                                        />
                                    </div>
                                    <div class="row">
                                        <x-text-input
                                                name="level02"
                                                label="Nivel 2"
                                                colClass="col-lg-2 col-md-12"
                                        />
                                        <x-text-input
                                                name="timeout02"
                                                label="Tiempo de espera"
                                                colClass="col-lg-2 col-md-12"
                                        />
                                    </div>
                                    <div class="row">
                                        <x-text-input
                                                name="level03"
                                                label="Nivel 3"
                                                colClass="col-lg-2 col-md-12"
                                        />
                                        <x-text-input
                                                name="timeout03"
                                                label="Tiempo de espera"
                                                colClass="col-lg-2 col-md-12"
                                        />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Detalle de Nivel</div>
                <div class="card-body">
                    <table class="table table-condensed table-bordered">
                        <thead>
                        <tr>
                            <th>Responsable</th>
                            <th>Sub-Categoría</th>
                            <th>Nivel 01</th>
                            <th>Tiempo de espera 01</th>
                            <th>Nivel 02</th>
                            <th>Tiempo de espera 02</th>
                            <th>Nivel 03</th>
                            <th>Tiempo de espera 03</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('css')

@endsection

@section('script')

@endsection
