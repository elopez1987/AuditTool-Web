@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Asignación de datos complementos
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folionumber">Folio de unidad</label>
                                    <input type="text" class="form-control numeric"  id="folionumber" name="folionumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placa de caja</label>
                                    <input type="text" class="form-control"  id="boxnumber" name="boxnumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 ">
                            <table id="table-assignshipment"
                                   class="table table-bordered table-striped table-condensed table-hover table-uppercase table-responsive">
                                <thead>
                                <tr class="bg-inverse">
                                    <th class="text-white">Folio</th>
                                    <th class="text-white">Código de barra</th>
                                    <th class="text-white">Linea de transporte</th>
                                    <th class="text-white">Placas de caja</th>
                                    <th class="text-white">Número de caja</th>
                                    <th class="text-white">Operador</th>
                                    <th class="text-white">Hora de llegada</th>
                                    <th class="text-white">Embarque</th>
                                    <th class="text-white">Responsable</th>
                                    <th class="text-white">Area</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')

@endsection


@section('script')
    @include('Yards.AssignShipment.partials.table')
    <script>
        function reloadtable() {
            $('#table-assignshipment').DataTable().ajax.reload();
        }
    </script>
    <script>
        $(document).on('shown.bs.modal', '.bs-example-modal-lg', function () {
            $(this).find('#responsibleid').select2({
                ajax: {
                    url: '{{ url('getUsersForResponsable') }}',
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    processResults: function (data) {
                        return {
                            results: data.map(function (item) {
                                return { id: item.id, text: item.name };
                            })
                        };
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al cargar datos:', error);
                    }
                },
                placeholder: 'Seleccione un responsable',
                allowClear: true,
                minimumInputLength: 1,
                dropdownParent: $(this).find('.modal-content')
            });
            $(this).find('#areaid').select2({
                ajax: {
                    url: '{{ url('getAreasForResponsable') }}',
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    processResults: function (data) {
                        return {
                            results: data.map(function (item) {
                                return { id: item.id, text: item.name };
                            })
                        };
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al cargar datos:', error);
                    }
                },
                placeholder: 'Seleccione un responsable',
                allowClear: true,
                minimumInputLength: 1,
                dropdownParent: $(this).find('.modal-content')
            });

        });
        $(document).on('hidden.bs.modal', '.bs-example-modal-md', function () {
            $(this).find('#responsibleid').select2('destroy');
            $(this).find('#areaid').select2('destroy');
        });
    </script>


@endsection
