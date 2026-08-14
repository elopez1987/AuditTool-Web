@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Asignación de cédulas
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{url('inventoryheads/' . $inventoryhead->inventory_uuid)}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/back.png')}}" alt="" class="img-16 ml-1">
                                    <span class="text-button ml-3 mr-3"> Salir</span>
                                </button>
                            </a>
                        </div>
                    </div>
                    <hr class="mt-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">Asignación de conteo 1</div>
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form method="POST"
                                                  action="{{url('save-accountant-cards/' . $inventoryhead->id)}}"
                                                  id="form-add-aisle-count">
                                                {{ method_field('PUT') }}
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <button type="submit"
                                                                class="btn waves-effect waves-light btn-outline-success btn-block btn-md"
                                                                id="btn-save-aisle-count">
                                                            <img src="{{asset('/ico/save02.png')}}" alt=""
                                                                 class="img-16">
                                                            <span class="text-button"> Guardar</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <hr class="mb-3">
                                                <div class="row">
                                                    <x-select2
                                                            name="user_id"
                                                            label="Contador"
                                                            :options="$usersAccountants"
                                                            valueField="user_id"
                                                            textField="name"
                                                            :required="true"
                                                            colClass="col-lg-6 col-md-12"
                                                    />
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="card p-3">
                                                            <select multiple="multiple" size="30"
                                                                    class="duallistbox-with-filter"
                                                                    name="inventory_card01_id[]"
                                                                    id="inventory_card01_id" required>
                                                                @foreach($cardscount01 as $card01)
                                                                    <option value="{{$card01->id}}"
                                                                            style="font-size: 14px !important;text-transform: uppercase">
                                                                        {{$card01->name}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card p-3">
                                                <table class="table table-bordered table-condensed" id="table-count-01">
                                                    <thead>
                                                    <tr>
                                                        <th>Contador</th>
                                                        <th>Cédula para contar</th>
{{--                                                        <th>Opciones</th>--}}
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">Asignación de conteo 2</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form method="POST"
                                                  action="{{url('save-accountant-cards-02/' . $inventoryhead->id)}}"
                                                  id="form-add-aisle-count">
                                                {{ method_field('PUT') }}
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <button type="submit"
                                                                class="btn waves-effect waves-light btn-outline-success btn-block btn-md"
                                                                id="btn-save-aisle-count">
                                                            <img src="{{asset('/ico/save02.png')}}" alt=""
                                                                 class="img-16">
                                                            <span class="text-button"> Guardar</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <hr class="mb-3">
                                                <div class="row">
                                                    <x-select2
                                                            name="user_id_02"
                                                            label="Contador"
                                                            :options="$usersAccountants02"
                                                            valueField="user_id"
                                                            textField="name"
                                                            :required="true"
                                                            colClass="col-lg-6 col-md-12"
                                                    />
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="card p-3">
                                                            <select multiple="multiple" size="30"
                                                                    class="duallistbox-with-filter"
                                                                    name="inventory_card02_id[]"
                                                                    id="inventory_card02_id" required>
                                                                @foreach($cardscount02 as $card01)
                                                                    <option value="{{$card01->id}}" style="font-size: 14px !important;text-transform: uppercase">
                                                                        {{$card01->name}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card p-3">
                                                <table class="table table-bordered table-condensed" id="table-count-01">
                                                    <thead>
                                                    <tr>
                                                        <th>Contador</th>
                                                        <th>Cédula para contar</th>
                                                        {{--                                                        <th>Opciones</th>--}}
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
                </div>
            </div>
        </div>


        <hr class="mb-3">
        @include('layouts.Partials.Errors')
        @endsection

        @section('css')
            <link rel="stylesheet"
                  href="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css')}}">
        @endsection

        @section('script')
            <script src="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>
            <script>
                const dualListbox = $('.duallistbox-with-filter').bootstrapDualListbox({
                    nonSelectedListLabel: 'No hay registros selecionados:',
                    selectedListLabel: 'Seleccionado',
                    preserveSelectionOnMove: 'Mover',
                    moveOnSelect: false,
                });
                $(function () {
                    $('.moveall i').removeClass().addClass('fas fa-angle-right');
                    $('.removeall i').removeClass().addClass('fas fa-angle-left');
                    $('.move i').removeClass().addClass('fas fa-angle-right');
                    $('.remove i').removeClass().addClass('fas fa-angle-left');
                });

            </script>

            <script>
                // Al seleccionar un contador para el conteo 1, se consultan las
                // cedulas disponibles (sin usuario en conteo 1, y sin usuario en
                // conteo 2 o con uno distinto al seleccionado) y se usan para
                // llenar el select multiple inventory_card01_id.
                $('#user_id').on('change', function () {
                    const userId = $(this).val();
                    const $cardSelect = $('#inventory_card01_id');

                    $cardSelect.empty();

                    if (!userId) {
                        $cardSelect.bootstrapDualListbox('refresh');

                        return;
                    }

                    $.get('{{ route("getCardForCount01", $inventoryhead->id) }}', {user_id: userId})
                        .done(function (cards) {
                            $.each(cards, function (index, card) {
                                $cardSelect.append(
                                    $('<option></option>')
                                        .val(card.id)
                                        .text(card.name)
                                        .css({'font-size': '14px', 'text-transform': 'uppercase'})
                                );
                            });

                            $cardSelect.bootstrapDualListbox('refresh');
                        })
                        .fail(function () {
                            $cardSelect.bootstrapDualListbox('refresh');
                            alert('No se pudieron obtener las cédulas disponibles para el conteo 1.');
                        });
                });
            </script>

            <script>
                // Al seleccionar un contador para el conteo 2, se consultan las
                // cedulas disponibles (sin usuario en conteo 2, y sin usuario en
                // conteo 1 o con uno distinto al seleccionado) y se usan para
                // llenar el select multiple inventory_card02_id.
                $('#user_id_02').on('change', function () {
                    const userId = $(this).val();
                    const $cardSelect = $('#inventory_card02_id');

                    $cardSelect.empty();

                    if (!userId) {
                        $cardSelect.bootstrapDualListbox('refresh');

                        return;
                    }

                    $.get('{{ route("getCardForCount02", $inventoryhead->id) }}', {user_id: userId})
                        .done(function (cards) {
                            $.each(cards, function (index, card) {
                                $cardSelect.append(
                                    $('<option></option>')
                                        .val(card.id)
                                        .text(card.name)
                                        .css({'font-size': '14px', 'text-transform': 'uppercase'})
                                );
                            });

                            $cardSelect.bootstrapDualListbox('refresh');
                        })
                        .fail(function () {
                            $cardSelect.bootstrapDualListbox('refresh');
                            alert('No se pudieron obtener las cédulas disponibles para el conteo 2.');
                        });
                });
            </script>

            <script>
                var oTable = $("#table-count-01").DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "sScrollY": "500px",
                    "processing": true,
                    "serverSide": true,
                    "autoFill": true,
                    "colReorder": true,
                    "stateSave": true,
                    "stateDuration": 0,
                    "responsive": true,

                    "ajax": {
                        url: '{{ route("getAccountantCards01", $inventoryhead->id) }}',
                        data: function (d) {
                            d.folionumber = $('input[name=folionumber]').val();
                            d.boxnumber = $('input[name=boxnumber]').val();
                            d.barcode = $('input[name=barcode]').val();
                            d.carrierid = $('select[name=carrierid]').val();
                        }
                    },
                    "columns": [
                        {data: 'user_count01.name', name: 'user_count01.name'},
                        {data: 'inventorycard.name', name: 'inventorycard.name'},
                        // {data: 'btn'},
                    ],
                    "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                    "lengthMenu": [[25, 50, -1], [25, 50, "All"]],

                    buttons: [
                        {
                            "extend": "colvis",
                            "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Mostar/Ocultar</span>",
                            "className": "btn btn-outline-info btn-md  btn-bold",
                            columns: ':not(:first):not(:last)'
                        },
                        {
                            "extend": "csv",
                            "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Csv</span>",
                            "className": "btn btn-outline-info btn-md  btn-bold",
                        },
                        {
                            "extend": "excel",
                            "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Excel</span>",
                            "className": "btn btn-outline-info btn-md  btn-bold",
                        }
                    ],

                    language: {
                        "decimal": "",
                        "emptyTable": "No hay información",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Mostrar _MENU_ Entradas",
                        "loadingRecords": "Cargando...",
                        "processing": '<i class="fa fa-spinner fa-spin fa-4x fa-fw text-info"></i><span class="sr-only">Loading...</span> ',
                        "search": "Buscar:",
                        "zeroRecords": "Sin resultados encontrados",
                        "paginate": {
                            "first": "Primero",
                            "last": "Ultimo",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    },
                });
                $('#search').on('submit', function (e) {
                    oTable.draw();
                    e.preventDefault();
                });

            </script>



@endsection

