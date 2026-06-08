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
                        <div class="col-lg-6">
                            <form method="POST" action="{{url('addaislecounttoinventory/' . $inventoryhead->id)}}" id="form-add-aisle-count">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-2">
                                        <button type="submit" class="btn waves-effect waves-light btn-outline-success btn-block btn-md" id="btn-save-aisle-count">
                                            <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
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
                                    />
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <select multiple="multiple" size="30"  class="duallistbox-with-filter" name="inventory_aisle_id[]" id="inventory_aisle_id" required>
                                            @foreach($cards as $aisle)
                                                <option value="{{$aisle->id}}" style="font-size: 14px !important;text-transform: uppercase">
                                                    {{$aisle->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    <hr class="mb-3">
{{--    <div class="row">--}}
{{--        <div class="col-lg-12">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">--}}
{{--                    Editar Encabezado de Inventario - Cédulas--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-lg-6">--}}
{{--                            <form method="POST" action="{{url('addcardcounttoinventory/' . $inventoryhead->id)}}" id="form-add-card-count">--}}
{{--                                {{ method_field('PUT') }}--}}
{{--                                @csrf--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-2">--}}
{{--                                        <button type="submit" class="btn waves-effect waves-light btn-outline-success btn-block btn-md" id="btn-save-card-count">--}}
{{--                                            <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">--}}
{{--                                            <span class="text-button"> Guardar</span>--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <hr class="mb-3">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-lg-12">--}}
{{--                                        <select multiple="multiple" size="30"  class="duallistbox-with-filter" name="inventory_card_id[]" id="inventory_card_id" required>--}}
{{--                                            @foreach($cards as $card)--}}
{{--                                                <option value="{{$card->id}}" style="font-size: 14px !important;text-transform: uppercase">--}}
{{--                                                    {{$card->name}}--}}
{{--                                                </option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                        <div class="col-lg-6">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-lg-12">--}}
{{--                                    <table class="table table-bordered table-condensed " id="table-card-inventory-count" width="100%">--}}
{{--                                        <thead>--}}
{{--                                        <tr>--}}
{{--                                            <th>Cédula</th>--}}
{{--                                            <th>Actions</th>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                    </table>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--        </div>--}}

{{--    </div>--}}

     @include('layouts.Partials.Errors')
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>
{{--    @include('Inventory.InventoryHead.partials.table-aisle-inventory-count')--}}
{{--    @include('Inventory.InventoryHead.partials.table-card-inventory-count')--}}
    <script>
        const dualListbox = $('.duallistbox-with-filter').bootstrapDualListbox({
            nonSelectedListLabel: 'No hay registros selecionados:',
            selectedListLabel: 'Seleccionado',
            preserveSelectionOnMove: 'Mover',
            moveOnSelect: false,
        });
        const getAvailableAislesForInventoryUrl = "{{ route('getAvailableAislesForInventory', ['id' => $inventoryhead->id]) }}";
        const getAvailableCardsForInventoryUrl = "{{ route('getAvailableCardsForInventory', ['id' => $inventoryhead->id]) }}";
        $(function () {
            $('.moveall i').removeClass().addClass('fas fa-angle-right');
            $('.removeall i').removeClass().addClass('fas fa-angle-left');
            $('.move i').removeClass().addClass('fas fa-angle-right');
            $('.remove i').removeClass().addClass('fas fa-angle-left');
        });

        function refreshAislesDualListbox() {
            $.ajax({
                url: getAvailableAislesForInventoryUrl,
                type: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    const aisles = response.data || [];
                    const select = $('#inventory_aisle_id');
                    select.empty();

                    aisles.forEach((aisle) => {
                        select.append(
                            $('<option>', {
                                value: aisle.id,
                                text: aisle.name
                            })
                        );
                    });

                    select.bootstrapDualListbox('refresh', true);
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: getAjaxErrorMessage(xhr, 'Error al actualizar la lista de pasillos')
                    });
                }
            });
        }

        function refreshCardsDualListbox() {
            $.ajax({
                url: getAvailableCardsForInventoryUrl,
                type: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    const cards = response.data || [];
                    const select = $('#inventory_card_id');
                    select.empty();

                    cards.forEach((card) => {
                        select.append(
                            $('<option>', {
                                value: card.id,
                                text: card.name
                            })
                        );
                    });

                    select.bootstrapDualListbox('refresh', true);
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: getAjaxErrorMessage(xhr, 'Error al actualizar la lista de cédulas')
                    });
                }
            });
        }

        function getAjaxErrorMessage(xhr, defaultMessage) {
            let message = defaultMessage;

            if (xhr.responseJSON) {
                if (xhr.responseJSON.errors) {
                    const firstKey = Object.keys(xhr.responseJSON.errors)[0];
                    if (firstKey && xhr.responseJSON.errors[firstKey].length > 0) {
                        message = xhr.responseJSON.errors[firstKey][0];
                    }
                } else if (xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
            }

            return message;
        }

        $('#form-add-aisle-count').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const submitBtn = $('#btn-save-aisle-count');

            submitBtn.prop('disabled', true);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        text: response.message || 'Pasillos asignados al inventario con éxito!!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    refreshAislesDualListbox();
                    reloadAisleInventoryCountTable();

                    refreshCardsDualListbox();
                    reloadCardInventoryCountTable();
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: getAjaxErrorMessage(xhr, 'Error al guardar los pasillos del inventario')
                    });
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                }
            });
        });

        function deleteAisleCountFromInventory(id) {
            Swal.fire({
                text: '¿Deseas eliminar este registro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: `{{ url('deleteAisleCountFromInventory') }}/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            text: response.message || 'Pasillo eliminado del conteo con éxito!!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        refreshAislesDualListbox();
                        reloadAisleInventoryCountTable();
                        refreshCardsDualListbox();
                        reloadCardInventoryCountTable();
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: getAjaxErrorMessage(xhr, 'Error al eliminar el pasillo del conteo')
                        });
                    }
                });
            });
        }

        $('#form-add-card-count').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const submitBtn = $('#btn-save-card-count');

            submitBtn.prop('disabled', true);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        text: response.message || 'Cédulas asignadas al inventario con éxito!!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    refreshCardsDualListbox();
                    reloadCardInventoryCountTable();
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: getAjaxErrorMessage(xhr, 'Error al guardar las cédulas del inventario')
                    });
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                }
            });
        });

        function deleteCardCountFromInventory(id) {
            Swal.fire({
                text: '¿Deseas eliminar este registro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: `{{ url('deleteCardCountFromInventory') }}/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            text: response.message || 'Cédula eliminada del conteo con éxito!!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        refreshCardsDualListbox();
                        reloadCardInventoryCountTable();
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: getAjaxErrorMessage(xhr, 'Error al eliminar la cédula del conteo')
                        });
                    }
                });
            });
        }
    </script>


@endsection

