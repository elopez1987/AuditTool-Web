const assert = require('node:assert/strict');
const { readFileSync } = require('node:fs');
const { test } = require('node:test');
const vm = require('node:vm');

const view = readFileSync(
    new URL('../../resources/views/Receipt/ReceiptAsn/addStock.blade.php', `file://${__filename}`),
    'utf8',
);
const script = view.match(/<script type="module">([\s\S]*?)<\/script>/)[1];
const routeExpression = "{{ Illuminate\\Support\\Js::from(route('inventory-partnumbers.search', ['prtnum' => '__PRTNUM__'], false)) }}";

function setup({ code = 'SKU-001', status = 200, data = { Data: { description: 'Material One' } }, hasFields = true } = {}) {
    const listeners = {};
    const fields = {
        prtnum: { value: code, addEventListener: (event, listener) => { listeners[event] = listener; } },
        lngdsc: { value: 'Previous description' },
    };
    const requests = [];
    const alerts = [];

    assert.ok(script.includes(routeExpression), 'The lookup must use the named Laravel route');

    vm.runInNewContext(script.replace(routeExpression, JSON.stringify('/inventory-partnumbers/__PRTNUM__/search')), {
        document: {
            addEventListener: (event, listener) => { listener(); },
            getElementById: (id) => hasFields ? fields[id] : null,
        },
        fetch: async (url, options) => {
            requests.push({ url, options });
            return { ok: status >= 200 && status < 300, status, json: async () => data };
        },
        Swal: { fire: (alert) => { alerts.push(alert); } },
        console: { error: () => {} },
    });

    return { fields, listeners, requests, alerts };
}

test('loads the description from the searchBySku response on blur', async () => {
    const { fields, listeners, requests, alerts } = setup();

    await listeners.blur();

    assert.equal(fields.lngdsc.value, 'Material One');
    assert.equal(requests[0].url, '/inventory-partnumbers/SKU-001/search');
    assert.equal(requests[0].options.headers.Accept, 'application/json');
    assert.equal(alerts.length, 0);
});

test('trims and URL-encodes the entered part number', async () => {
    const { listeners, requests } = setup({ code: '  SKU #01  ' });

    await listeners.blur();

    assert.equal(requests[0].url, '/inventory-partnumbers/SKU%20%2301/search');
});

test('clears the description and does not search an empty part number', async () => {
    const { fields, listeners, requests, alerts } = setup({ code: '   ' });

    await listeners.blur();

    assert.equal(fields.lngdsc.value, '');
    assert.equal(requests.length, 0);
    assert.equal(alerts[0].text, 'Ingrese el número de parte');
});

test('clears the fields when the material does not exist', async () => {
    const { fields, listeners, alerts } = setup({ status: 404, data: { message: 'No encontrado' } });

    await listeners.blur();

    assert.equal(fields.prtnum.value, '');
    assert.equal(fields.lngdsc.value, '');
    assert.equal(alerts[0].text, 'No se encuentran registros del Sku!');
});

test('reports HTTP errors without retaining the previous description', async () => {
    const { fields, listeners, alerts } = setup({ status: 500 });

    await listeners.blur();

    assert.equal(fields.lngdsc.value, '');
    assert.equal(alerts[0].text, 'Ocurrió un error al consultar el SKU.');
});

test('does not register a lookup when the receipt has no editable fields', () => {
    const { listeners, requests } = setup({ hasFields: false });

    assert.equal(listeners.blur, undefined);
    assert.equal(requests.length, 0);
});
