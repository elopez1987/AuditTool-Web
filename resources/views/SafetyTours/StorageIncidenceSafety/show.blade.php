@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <a href="{{route('storage-incidence-safeties.index')}}">
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
                                        Incidencia : {{$storage_incidence_safety->id}}
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title">{{$storage_incidence_safety->name  }}</h3>
                                    <p class="card-text">{{$storage_incidence_safety->description}}</p>
                                    <span class="text-muted float-right">{{$storage_incidence_safety->closed_at}}</span>
                                    <span class="label label-rounded label-primary">{{$storage_incidence_safety->status->name}}</span>
                                    <span class="action-icons"></span>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Creado por:</h4>
                                    <p class="card-text">{{$storage_incidence_safety->createuser->name}}</p>
                                    <span class="text-muted float-right">{{$storage_incidence_safety->created_at}}</span>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Cerrado por:</h4>
                                    <p class="card-text">{{$storage_incidence_safety->closeduser->name}}</p>
                                    <span class="text-muted float-right">{{$storage_incidence_safety->closed_at}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                @foreach($photos as $photo)
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <img class="card-img-top img-responsive"  src="{{asset('storage/' . $photo->path)}}" alt="Card image">
                                            <div class="card-body">
                                                <h6 class="card-title">{{$photo->createuser->name}}</h6>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        {{$photo->created_at}}
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
