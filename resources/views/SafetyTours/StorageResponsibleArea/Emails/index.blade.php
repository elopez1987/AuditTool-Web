@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar Área
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="POST"
                                  action="{{url('responsible-area-emails-store/' . $storageResponsibleArea->id)}}"
                                  enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <a href="">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('responsible-area-security.index')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mb-3">
                                <div class="row">
                                    <x-text-input
                                            name="namearea"
                                            label="Nombre del área responsable:"
                                            disabled="disabled"
                                            :value="$storageResponsibleArea->name"
                                    />
                                </div>
                                <div class="row">
                                    <x-text-input
                                            name="name"
                                            label="Nombre completo"
                                            required
                                            uppercase
                                    />
                                    <x-text-input
                                            name="email"
                                            label="Correo electronico"
                                            required
                                            uppercase
                                    />

                                </div>
                                <div class="row">
                                    <x-text-input
                                            name="phone"
                                            label="Número de contacto"
                                            maxlength="10"
                                            required
                                            uppercase
                                    />
                                    <x-text-input
                                            name="level"
                                            label="Nivel de contacto"
                                            maxlength="1"
                                            required
                                    />
                                    <x-text-input
                                            name="attempts"
                                            label="Cantidad de correos a enviar"
                                            maxlength="1"
                                            required
                                    />

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Detalle de contactos
                </div>
                <div class="card-body">
                    <table class="table table-condensed table-bordered tablegeneral">
                        <thead>
                        <tr>
                            <th>Nombre completo</th>
                            <th>Correo electrónico</th>
                            <th>Número de contacto</th>
                            <th>Nivel de notificación</th>
                            <th>Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($contacts as $contact)
                            <tr>
                                <td>{{$contact->name}}</td>
                                <td>{{$contact->email}}</td>
                                <td>{{$contact->phone}}</td>
                                <td>{{$contact->level}}</td>
                                <td>
                                    {{-- Modificar --}}
                                    <a href="" data-target="#modal-update-{{$contact->id}}"
                                       data-toggle="modal">
                                        <img src="{{asset('/ico/typemovements.png')}}" alt=""
                                             class="img-16 ml-2">
                                    </a>
                                    @include('SafetyTours.StorageResponsibleArea.Emails.update')
                                    {{-- Eliminar --}}
                                    <a href="" data-target="#modal-delete-{{$contact->id}}"
                                       data-toggle="modal">
                                        <img src="{{asset('/ico/destroy2.png')}}" alt=""
                                             class="img-16 ml-2">
                                    </a>
                                    @include('SafetyTours.StorageResponsibleArea.Emails.delete')
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.Partials.Errors')
@endsection

@section('script')
    @include('layouts.Partials.DataTable')
@endsection

