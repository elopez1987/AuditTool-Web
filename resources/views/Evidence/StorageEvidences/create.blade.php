@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Crear Registro de evidencia
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storageevidenceprocess')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('storageevidenceprocess.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storageevidenceprocess.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Evidence.StorageEvidences.partials.formCreate')
                    </form>
                </div>
                <div class="card-footer text-center">
                    Evidencias / Evidencias de Proceso / Crear nuevo registro
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')


    <script>
        $("#razonstatusid").change(event => {
            let razonstatusid = document.getElementById("razonstatusid").value;
            if (razonstatusid === '1') {
                $("#provider").hide();
                $("#customer").show();
            }
            if (razonstatusid === '2') {
                $("#customer").hide();
                $("#provider").show();
            }
        });
    </script>
@endsection

