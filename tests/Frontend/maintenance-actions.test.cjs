const assert = require('node:assert/strict');
const { readFileSync } = require('node:fs');
const { test } = require('node:test');
const vm = require('node:vm');

const script = readFileSync(new URL('../../public/js/maintenance-actions.js', `file://${__filename}`), 'utf8');

function setup(confirmed) {
    let submit;
    let confirmations = 0;
    class Form {
        constructor(maintenance = true) {
            this.maintenance = maintenance;
        }
        matches(selector) {
            assert.equal(selector, '[data-maintenance-action]');
            return this.maintenance;
        }
    }
    vm.runInNewContext(script, {
        HTMLFormElement: Form,
        document: {
            addEventListener(event, listener) {
                assert.equal(event, 'submit');
                submit = listener;
            },
        },
        window: {
            confirm() {
                confirmations++;
                return confirmed;
            },
        },
    });
    return { submit, Form, confirmations: () => confirmations };
}

test('cancels a maintenance submission when the user declines', () => {
    const { submit, Form, confirmations } = setup(false);
    let prevented = false;
    submit({ target: new Form(), preventDefault: () => { prevented = true; } });
    assert.equal(prevented, true);
    assert.equal(confirmations(), 1);
});

test('allows confirmed maintenance submissions', () => {
    const { submit, Form } = setup(true);
    submit({ target: new Form(), preventDefault: () => assert.fail('Confirmed submit was blocked') });
});

test('does not intercept unrelated forms or non-form targets', () => {
    const { submit, Form, confirmations } = setup(false);
    submit({ target: new Form(false), preventDefault: () => assert.fail('Unrelated form was blocked') });
    submit({ target: {}, preventDefault: () => assert.fail('Unrelated target was blocked') });
    assert.equal(confirmations(), 0);
});
