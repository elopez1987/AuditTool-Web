@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-10">
            <div class="card">
                <div class="card-header">
                    Folios de conteo de andenes
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                            </div>
                        </div>

                        <hr class="mt-4">
                    </form>
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-folios" class="table table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Bodega</th>
                                    <th class="bg-inverse text-white">Folio</th>
                                    <th class="bg-inverse text-white">Fecha de trabajo</th>
                                    <th class="bg-inverse text-white">Creador</th>
                                    <th class="bg-inverse text-white">Estatus</th>
                                    <th class="bg-inverse text-white">Estatus de firma</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('Shipments.Bridgestone.ShipmentFolios.partials.table')
    <script>
        function reloadtable() {
            $('#table-folios').DataTable().ajax.reload();
        }
    </script>

    <script>

        document.addEventListener("DOMContentLoaded", () => {

            // Eliminar registro
            document.addEventListener("click", async (e) => {

                const btn = e.target.closest(".delete-folio");
                if (!btn) return;

                e.preventDefault();

                const uuid = btn.dataset.uuid;

                if (!uuid) {
                    console.error("UUID no encontrado");
                    return;
                }

                // Mostrar confirmación
                const swal = await Swal.fire({
                    title: "¿Estás seguro?",
                    text: "Este registro será eliminado permanentemente.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar",
                    confirmButtonColor: "#d33",
                });

                if (!swal.isConfirmed) return;

                try {
                    const response = await fetch(`/storageshipmentfolios/${uuid}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Accept": "application/json"
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        await Swal.fire({
                            title: "Eliminado",
                            text: result.message || "El registro se eliminó correctamente.",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                        // Recargar DataTable
                        $('#table-folios').DataTable().ajax.reload();
                    } else {
                        throw result;
                    }

                } catch (error) {
                    console.error(error);

                    Swal.fire({
                        title: "Error",
                        text: error.message || "No se pudo eliminar el registro.",
                        icon: "error"
                    });
                }
            });
        });
    </script>
@endsection
