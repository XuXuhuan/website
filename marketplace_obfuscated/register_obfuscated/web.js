const _0x8a29 = [
    '<button\x20id=\x22marketRegisterButton\x22\x20onmouseup=\x22submitMarketRegister(event)\x22\x20onmousedown=\x22cancelMarketRegisterTimeout(event)\x22>Register</button>',
    'text',
    'openedSideNav',
    'emailError',
    'message',
    'menuAnimationClose',
    '#sidenav',
    '#menuToggle',
    'toggle',
    'click',
    'Content-type',
    'animationName',
    'Content-Type',
    'style',
    'open',
    'length',
    'innerHTML',
    'marketname=',
    'marketCategories',
    'classList',
    'send',
    'json',
    'mouseup',
    'cursor',
    'filter',
    'Market\x20name\x20has\x20to\x20be\x20under\x2030\x20characters.',
    'An\x20internal\x20server\x20error\x20occurred.\x20Please\x20try\x20again\x20later.',
    'pointer',
    'responseText',
    'application/x-www-form-urlencoded',
    'tickedCategoryBox',
    'button',
    '#marketNameField',
    'remove',
    'POST',
    '#marketNameError',
    'stringify',
    'application/json',
    '.marketCategoryBox',
    'Market\x20name\x20must\x20contain\x20at\x20least\x203\x20characters.',
    'onload',
    'contains',
    'setRequestHeader',
    'XMLHttpRequest',
    'querySelectorAll',
    'addEventListener',
    'errormessages',
    'brightness(100%)',
    'response',
    'registerMarkplace.php',
    '#marketRegisterButton',
    'marketNameTaken.php',
    'forEach',
    'keydown',
    '<div\x20id=\x22loadingImageCont\x22></div>',
    'menuAnimationOpen',
    'value',
    'Microsoft.XMLHTTP',
    'keyup',
    'querySelector',
    '#registerMessage',
    'add',
    'This\x20field\x20is\x20required.',
    '#marketRegisterButtonCont',
    'responseType',
    'status'
];
(function (_0x41479e, _0x8a294b) {
    const _0x4dd248 = function (_0x45a753) {
        while (--_0x45a753) {
            _0x41479e['push'](_0x41479e['shift']());
        }
    };
    _0x4dd248(++_0x8a294b);
}(_0x8a29, 0x1c7));
const _0x4dd2 = function (_0x41479e, _0x8a294b) {
    _0x41479e = _0x41479e - 0x0;
    let _0x4dd248 = _0x8a29[_0x41479e];
    return _0x4dd248;
};
const _0x578314 = _0x4dd2;
'use strict';
const _0x45a753 = document[_0x578314('0x0')](_0x578314('0xe'));
const _0x4e2409 = document[_0x578314('0x0')](_0x578314('0xd'));
const _0x45d46f = document[_0x578314('0x33')](_0x578314('0x2d'));
const _0x1776fc = document[_0x578314('0x0')](_0x578314('0x27'));
const _0x84b44b = document[_0x578314('0x0')](_0x578314('0x2a'));
const _0x1d5881 = document[_0x578314('0x0')](_0x578314('0x39'));
const _0x506c5f = document[_0x578314('0x0')](_0x578314('0x1'));
const _0x26bb06 = document[_0x578314('0x0')](_0x578314('0x4'));
var _0x48dc2e;
var _0x220317;
var _0x2caba2 = {
    'marketName': '',
    'marketCategories': {
        'automotive': ![],
        'babyCare': ![],
        'books': ![],
        'CDandVinyl': ![],
        'clothesAndAccessories': ![],
        'electronics': ![],
        'gardening': ![],
        'outdoorsAndSports': ![],
        'groceries': ![],
        'health': ![],
        'household': ![],
        'personalCare': ![],
        'kitchenAndDining': ![],
        'travelSupplies': ![],
        'beauty': ![],
        'moviesAndTV': ![],
        'musicalInstruments': ![],
        'officeSupplies': ![],
        'petSupplies': ![],
        'software': ![],
        'tools': ![],
        'toys': ![],
        'games': ![]
    }
};
_0x45a753[_0x578314('0x14')][_0x578314('0x1f')] = _0x578314('0x36');
_0x45a753[_0x578314('0x14')][_0x578314('0x1e')] = _0x578314('0x22');
function submitMarketRegister(_0x248fb8) {
    const _0x2ce5a9 = _0x578314;
    if (_0x248fb8[_0x2ce5a9('0x26')] === 0x0) {
        clearTimeout(_0x48dc2e);
        clearTimeout(_0x220317);
        var _0x8ee725 = 0x0;
        _0x45d46f[_0x2ce5a9('0x3b')](function (_0x4dd365) {
            const _0x44fb1c = _0x2ce5a9;
            if (_0x4dd365[_0x44fb1c('0x1a')][_0x44fb1c('0x30')](_0x44fb1c('0x25'))) {
                _0x8ee725++;
            }
        });
        if (_0x1776fc[_0x2ce5a9('0x3f')][_0x2ce5a9('0x16')] > 0x3 && _0x1776fc[_0x2ce5a9('0x3f')][_0x2ce5a9('0x16')] < 0x1e && _0x8ee725 > 0x0) {
            _0x48dc2e = setTimeout(function () {
                const _0x55ff6e = _0x2ce5a9;
                const _0x264d95 = window[_0x55ff6e('0x32')] ? new XMLHttpRequest() : new ActiveXObject(_0x55ff6e('0x40'));
                _0x264d95[_0x55ff6e('0x15')](_0x55ff6e('0x29'), _0x55ff6e('0x38'), !![]);
                _0x264d95[_0x55ff6e('0x31')](_0x55ff6e('0x13'), _0x55ff6e('0x2c'));
                _0x264d95[_0x55ff6e('0x5')] = _0x55ff6e('0x1c');
                _0x26bb06[_0x55ff6e('0x17')] = _0x55ff6e('0x3d');
                _0x264d95[_0x55ff6e('0x2f')] = function () {
                    const _0x30a30f = _0x55ff6e;
                    if (_0x264d95[_0x30a30f('0x6')] === 0xc8) {
                        _0x84b44b[_0x30a30f('0x17')] = _0x264d95[_0x30a30f('0x37')][_0x30a30f('0x35')][_0x30a30f('0xa')];
                        _0x506c5f[_0x30a30f('0x17')] = _0x264d95[_0x30a30f('0x37')][_0x30a30f('0xb')];
                        _0x26bb06[_0x30a30f('0x17')] = _0x30a30f('0x7');
                    } else {
                        _0x506c5f[_0x30a30f('0x17')] = _0x30a30f('0x21');
                    }
                };
                _0x264d95[_0x55ff6e('0x1b')](JSON[_0x55ff6e('0x2b')](_0x2caba2));
            }, 0x15e);
        }
    }
}
function cancelMarketRegisterTimeout(_0x981d82) {
    const _0x865885 = _0x578314;
    if (_0x981d82[_0x865885('0x26')] === 0x0) {
        clearTimeout(_0x48dc2e);
        clearTimeout(_0x220317);
    }
}
_0x45a753[_0x578314('0x34')](_0x578314('0x10'), function (_0x5b69ac) {
    const _0x1d898f = _0x578314;
    if (_0x5b69ac[_0x1d898f('0x26')] === 0x0) {
        if (_0x4e2409[_0x1d898f('0x1a')][_0x1d898f('0x30')](_0x1d898f('0x9')) || _0x45a753[_0x1d898f('0x14')][_0x1d898f('0x12')] === _0x1d898f('0x3e')) {
            _0x4e2409[_0x1d898f('0x1a')][_0x1d898f('0x28')](_0x1d898f('0x9'));
            _0x45a753[_0x1d898f('0x14')][_0x1d898f('0x12')] = _0x1d898f('0xc');
        } else {
            _0x4e2409[_0x1d898f('0x1a')][_0x1d898f('0x2')](_0x1d898f('0x9'));
            _0x45a753[_0x1d898f('0x14')][_0x1d898f('0x12')] = _0x1d898f('0x3e');
        }
    }
});
_0x45d46f[_0x578314('0x3b')](function (_0x39dac4, _0x489464) {
    const _0x2bc97b = _0x578314;
    _0x39dac4[_0x2bc97b('0x34')](_0x2bc97b('0x1d'), function (_0x154962) {
        const _0x293656 = _0x2bc97b;
        if (_0x154962[_0x293656('0x26')] === 0x0) {
            _0x39dac4[_0x293656('0x1a')][_0x293656('0xf')](_0x293656('0x25'));
            _0x2caba2[_0x293656('0x19')][_0x489464] = !_0x2caba2[_0x293656('0x19')][_0x489464];
        }
    });
});
_0x1776fc[_0x578314('0x34')](_0x578314('0x41'), function () {
    const _0x5da4ce = _0x578314;
    clearTimeout(_0x48dc2e);
    if (_0x1776fc[_0x5da4ce('0x3f')][_0x5da4ce('0x16')] === 0x0) {
        _0x84b44b[_0x5da4ce('0x17')] = _0x5da4ce('0x3');
    } else if (_0x1776fc[_0x5da4ce('0x3f')][_0x5da4ce('0x16')] < 0x3) {
        _0x84b44b[_0x5da4ce('0x17')] = _0x5da4ce('0x2e');
    } else if (_0x1776fc[_0x5da4ce('0x3f')][_0x5da4ce('0x16')] > 0x1e) {
        _0x84b44b[_0x5da4ce('0x17')] = _0x5da4ce('0x20');
    } else {
        _0x84b44b[_0x5da4ce('0x17')] = '';
        _0x48dc2e = setTimeout(function () {
            const _0x5337e9 = _0x5da4ce;
            const _0xb6349d = window[_0x5337e9('0x32')] ? new XMLHttpRequest() : new ActiveXObject(_0x5337e9('0x40'));
            _0xb6349d[_0x5337e9('0x15')](_0x5337e9('0x29'), _0x5337e9('0x3a'), !![]);
            _0xb6349d[_0x5337e9('0x31')](_0x5337e9('0x11'), _0x5337e9('0x24'));
            _0xb6349d[_0x5337e9('0x5')] = _0x5337e9('0x8');
            _0xb6349d[_0x5337e9('0x2f')] = function () {
                const _0x333a0a = _0x5337e9;
                if (_0xb6349d[_0x333a0a('0x6')] === 0xc8) {
                    _0x84b44b[_0x333a0a('0x17')] = _0xb6349d[_0x333a0a('0x23')];
                } else {
                    _0x84b44b[_0x333a0a('0x17')] = _0x333a0a('0x21');
                }
            };
            _0xb6349d[_0x5337e9('0x1b')](_0x5337e9('0x18') + encodeURIComponent(_0x1776fc[_0x5337e9('0x3f')]));
        }, 0x15e);
    }
});
_0x1776fc[_0x578314('0x34')](_0x578314('0x3c'), function () {
    clearTimeout(_0x48dc2e);
});