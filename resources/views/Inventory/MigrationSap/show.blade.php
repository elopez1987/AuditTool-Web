@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <a href="{{route('inventory-sap-migration')}}">
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
                        <div class="col-lg-4">
                            <div class="card border-success">
                                <div class="card-header bg-success">
                                    <h4 class="mb-0 text-white">
                                        Incidencia : {{$data->id}}
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title">Ubicación: {{$data->locationid  }}</h3>
                                    <h3 class="card-title">Lpn de Wms: {{$data->lpnizel  }}</h3>
                                    <h3 class="card-title">Lpn de SAP: {{$data->lpnsap  }}</h3>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <img class="card-img-top img-responsive"  src="{{asset('storage/' . $data->photo)}}" alt="Card image">
                                            <div class="card-body">
                                                <h6 class="card-title">{{$data->createduser->name}}</h6>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        {{$data->created_at}}
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                            </div>
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

@endsection
