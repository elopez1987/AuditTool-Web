const assert = require('node:assert/strict');
const { readFileSync } = require('node:fs');
const { test } = require('node:test');
const postcss = require('postcss');

const stylesheet = postcss.parse(readFileSync(
    new URL('../../public/css/app.css', `file://${__filename}`),
    'utf8',
));

function declaration(selector, property) {
    let value;
    stylesheet.walkRules((rule) => {
        if (rule.selectors.includes(selector)) {
            rule.walkDecls(property, (entry) => { value = entry.value; });
        }
    });
    assert.ok(value, `Missing compiled declaration: ${selector} / ${property}`);
    return value;
}

function luminance(hex) {
    const channels = hex.replace('#', '').match(/.{2}/g).map((channel) => {
        const value = Number.parseInt(channel, 16) / 255;
        return value <= 0.04045 ? value / 12.92 : ((value + 0.055) / 1.055) ** 2.4;
    });
    return channels[0] * 0.2126 + channels[1] * 0.7152 + channels[2] * 0.0722;
}

function assertContrast(foreground, background) {
    assert.match(foreground, /^#[0-9a-f]{6}$/i);
    assert.match(background, /^#[0-9a-f]{6}$/i);
    const values = [luminance(foreground), luminance(background)].sort((a, b) => b - a);
    const ratio = (values[0] + 0.05) / (values[1] + 0.05);
    assert.ok(ratio >= 4.5, `${foreground} on ${background} has insufficient contrast: ${ratio.toFixed(2)}:1`);
}

test('normal and secondary panel text meet AA contrast on white surfaces', () => {
    assertContrast(declaration(':root', '--bs-body-color'), '#ffffff');
    assertContrast(declaration(':root', '--bs-secondary-color'), '#ffffff');
});

test('table headings meet AA contrast on their compiled background', () => {
    assertContrast(
        declaration('table.dataTable thead th', '--bs-table-color'),
        declaration('table.dataTable thead th', '--bs-table-bg'),
    );
});

test('links and input placeholders remain readable on white surfaces', () => {
    assertContrast(declaration(':root', '--bs-link-color'), '#ffffff');
    assertContrast(declaration('.form-control::placeholder', 'color'), '#ffffff');
});

test('panel titles have a readable accent and form labels have clear emphasis', () => {
    assertContrast(declaration('.card-header', 'color'), '#ffffff');
    assert.equal(declaration('.form-group > label', 'font-weight'), '600');
});
