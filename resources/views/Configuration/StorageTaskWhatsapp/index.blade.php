@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Mensajes pendientes de enviar
                </div>
                <div class="card-body">
{{--                    <form method="GET" id="search" role="form">--}}
{{--                        {{csrf_field()}}--}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{route('sendmessagefornewarrivereceipt')}}">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md" data-toggle="tooltip" data-placement="top" title="Refrescar la pagina">
                                        <img src="{{asset('/ico/1486146469-telegram_79439.png')}}" alt="" class="img-16 ml-1">
                                        <span style="font-size: 0.675rem "> Procesar Mensajes Entrada</span>
                                    </button>
                                </a>
                                <a href="{{route('sendmessagefordifferenceweight')}}">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md" data-toggle="tooltip" data-placement="top" title="Refrescar la pagina">
                                        <img src="{{asset('/ico/1486146469-telegram_79439.png')}}" alt="" class="img-16 ml-1">
                                        <span style="font-size: 0.675rem "> Procesar Mensajes Pesos</span>
                                    </button>
                                </a>
                                <a>
                                    @include('buttons.reload')
                                </a>
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
{{--                    </form>--}}
                    <div class="card-input">
                        <div class="row">
                            <div class="col-lg-12 col-xs-12">
                                <table id="table-folio"
                                       class="table table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">C.Distribución</th>
                                        <th class="bg-inverse text-white">Bodega</th>
                                        <th class="bg-inverse text-white">ID Modelo</th>
                                        <th class="bg-inverse text-white">Modelo</th>
                                        <th class="bg-inverse text-white">Tipo</th>
                                        <th class="bg-inverse text-white">Auxiliar 01</th>
                                        <th class="bg-inverse text-white">Auxiliar 02</th>
                                        <th class="bg-inverse text-white">Auxiliar 03</th>
                                        <th class="bg-inverse text-white">Auxiliar 04</th>
                                        <th class="bg-inverse text-white">Auxiliar 05</th>
                                        <th class="bg-inverse text-white">Fecha de creación</th>
                                        <th class="bg-inverse text-white">Estatus</th>
                                        <th class="bg-inverse text-white">Opciones</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('Configuration.StorageTaskWhatsapp.partials.table')
    <script>
        function reloadtable() {
            $('#table-folio').DataTable().ajax.reload();
        }
    </script>
@endsection
