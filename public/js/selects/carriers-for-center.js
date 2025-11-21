// public/js/selects/carriers-for-center.js

export function initCarrierSelector({
                                        centerSelectId = "centerid",
                                        carrierSelectId = "carrierid",

                                    } = {}) {

    $(document).ready(function () {

        const $centerSelect = $("#" + centerSelectId);
        const $carrierSelect = $("#" + carrierSelectId);

        if ($centerSelect.length === 0 || $carrierSelect.length === 0) {
            return;
        }

        $centerSelect.on("change", async function () {
            const centerId = $(this).val();

            // Si no hay valor, limpiar select
            if (!centerId) {
                $carrierSelect.empty();
                $carrierSelect.append('<option value="">Seleccione un transportista</option>');
                return;
            }

            const url = `/shipment-carriers/${encodeURIComponent(centerId)}/search`;

            try {
                const response = await fetch(url);

                if (!response.ok) {
                    Swal.fire({
                        icon: "error",
                        title: "Error al consultar transportistas",
                        text: `Código de estado: ${response.status}`,
                    });
                    return;
                }

                const result = await response.json();

                if (!result.Data || !Array.isArray(result.Data) || result.Data.length === 0) {
                    Swal.fire({
                        icon: "info",
                        title: "Sin transportistas",
                        text: "No existen transportistas para este centro.",
                    });

                    $carrierSelect.empty();
                    $carrierSelect.append('<option value="">No hay transportistas</option>');
                    return;
                }

                // Limpiar y llenar select
                $carrierSelect.empty();
                $carrierSelect.append('<option value="">Seleccione un transportista</option>');

                result.Data.forEach(item => {
                    $carrierSelect.append(
                        $('<option></option>').val(item.id).text(item.name)
                    );
                });

            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Error de conexión",
                    text: "No se pudo obtener la información desde el servidor.",
                });
            }
        });
    });
}
