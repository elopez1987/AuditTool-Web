@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar Registro
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data"
                          action="{{url('storageevidenceprocess/' . $storageevidenceprocess->recordid)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storageevidenceprocess.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Evidence.StorageEvidences.partials.formEdit')
                    </form>
                </div>
                <div class="card-footer text-center">
                    Evidencias / Evidencias de Proceso / Modificar registro
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

