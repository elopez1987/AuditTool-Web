/**
 * Inventory Head - Cédulas asignadas por usuario (conteo 2)
 * Consume InventoryDashboardController@getTotalCardForUser
 */

$(document).ready(function () {

    // ── DataTable ────────────────────────────────────────────────────────────
    var tableUsersCards = initDataTable('#table-users-cards', {
        serverSide: true,
        ajax: {
            url: InventoryHeadUsersCards.ajaxUrl
        },
        columns: [
            {data: 'username'},
            {data: 'total'}
        ]
    });

    // ── Recargar tabla ───────────────────────────────────────────────────────
    window.reloadUsersCardsTable = function () {
        tableUsersCards.ajax.reload();
    };

});
