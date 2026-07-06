@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar detalle de check list de evidencias
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storage-check-listtruck-evidences/' . $checkList->id)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagechecklistlifttrucks.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <x-text-input
                                name="name"
                                label="Nombre completo"
                                required
                                uppercase
                                colClass="col-lg-4 col-md-12"
                        />
                    </form>
                    <div class="row">
                        <div class="col-8">
                            <table class="table table-condensed  table-bordered tablegeneral">
                                <thead>
                                <tr>
                                    <th>Evidencia</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($details as $detail)
                                    <tr>
                                        <td>{{$detail->name}}</td>
                                        <td>{{$detail->status ? $detail->status->name : ''}}</td>
                                        <td>
                                            <a href="" data-target="#modal-updatestatus-{{$detail->id}}"
                                               data-toggle="modal">
                                                <img src="{{asset('/ico/typemovements.png')}}" alt=""
                                                     class="img-16 ml-2">
                                            </a>
                                            @include('Maintenance.StorageCheckListEvidence.updatestatus')
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

@section('script')
    @include('layouts.Partials.DataTable')
@endsection

