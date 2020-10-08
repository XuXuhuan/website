const _0x3cbb = [
    'keydown',
    'pointer',
    'cursor',
    '#menuToggle',
    'XMLHttpRequest',
    'brightness(100%)',
    'Market\x20name\x20has\x20to\x20be\x20under\x2030\x20characters.',
    'style',
    '#marketRegisterButtonCont',
    'querySelectorAll',
    'response',
    'message',
    'marketCategories',
    '.marketCategoryBox',
    'animationName',
    'status',
    'marketNameTaken.php',
    'toggle',
    'Market\x20name\x20must\x20contain\x20at\x20least\x203\x20characters.',
    'marketname=',
    'openedSideNav',
    '<button\x20id=\x22marketRegisterButton\x22\x20onmouseup=\x22submitMarketRegister(event)\x22\x20onmousedown=\x22cancelMarketRegisterTimeout(event)\x22>Register</button>',
    'menuAnimationOpen',
    'click',
    'querySelector',
    'Content-type',
    'button',
    'innerHTML',
    'marketName',
    'application/json',
    '#sidenav',
    'Microsoft.XMLHTTP',
    'errormessages',
    'filter',
    'Success!',
    'classList',
    '#registerMessage',
    'keyup',
    'contains',
    '#marketNameError',
    'Content-Type',
    'forEach',
    'length',
    'remove',
    '#marketNameField',
    'responseType',
    'addEventListener',
    'value',
    '#marketRegisterButton',
    'text',
    'This\x20field\x20is\x20required.',
    'application/x-www-form-urlencoded',
    'onload',
    'responseText',
    'POST',
    'inputErrorText',
    'setRequestHeader',
    'stringify',
    'emailError',
    'json',
    'send',
    'registerMarkplace.php',
    'mouseup',
    'menuAnimationClose',
    'An\x20internal\x20server\x20error\x20occurred.\x20Please\x20try\x20again\x20later.',
    'open',
    '<div\x20id=\x22loadingImageCont\x22></div>',
    'tickedCategoryBox',
    'add'
];
(function (_0x5e25dc, _0x3cbb48) {
    const _0x2792cc = function (_0x41a66b) {
        while (--_0x41a66b) {
            _0x5e25dc['push'](_0x5e25dc['shift']());
        }
    };
    _0x2792cc(++_0x3cbb48);
}(_0x3cbb, 0x152));
const _0x2792 = function (_0x5e25dc, _0x3cbb48) {
    _0x5e25dc = _0x5e25dc - 0x0;
    let _0x2792cc = _0x3cbb[_0x5e25dc];
    return _0x2792cc;
};
const _0x3cda46 = _0x2792;
'use strict';
const _0x41a66b = document[_0x3cda46('0x1f')](_0x3cda46('0xa'));
const _0x23831a = document[_0x3cda46('0x1f')](_0x3cda46('0x25'));
const _0x48c96f = document[_0x3cda46('0x10')](_0x3cda46('0x14'));
const _0x52d029 = document[_0x3cda46('0x1f')](_0x3cda46('0x33'));
const _0x1aa17d = document[_0x3cda46('0x1f')](_0x3cda46('0x2e'));
const _0x5b1f58 = document[_0x3cda46('0x1f')](_0x3cda46('0x37'));
const _0x33301c = document[_0x3cda46('0x1f')](_0x3cda46('0x2b'));
const _0x4ada88 = document[_0x3cda46('0x1f')](_0x3cda46('0xf'));
var _0x15fd38;
var _0x466ecb;
var _0x5a7574 = {
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
_0x41a66b[_0x3cda46('0xe')][_0x3cda46('0x28')] = _0x3cda46('0xc');
_0x41a66b[_0x3cda46('0xe')][_0x3cda46('0x9')] = _0x3cda46('0x8');
function submitMarketRegister(_0x3fc885) {
    const _0x4728e2 = _0x3cda46;
    if (_0x3fc885[_0x4728e2('0x21')] === 0x0) {
        clearTimeout(_0x15fd38);
        clearTimeout(_0x466ecb);
        var _0x2e4ccf = 0x0;
        _0x48c96f[_0x4728e2('0x30')](function (_0x320dfe) {
            const _0x5755a2 = _0x4728e2;
            if (_0x320dfe[_0x5755a2('0x2a')][_0x5755a2('0x2d')](_0x5755a2('0x5'))) {
                _0x2e4ccf++;
            }
        });
        if (_0x52d029[_0x4728e2('0x36')][_0x4728e2('0x31')] > 0x3 && _0x52d029[_0x4728e2('0x36')][_0x4728e2('0x31')] < 0x1e && _0x2e4ccf > 0x0) {
            _0x15fd38 = setTimeout(function () {
                const _0x269542 = _0x4728e2;
                const _0x11a25f = window[_0x269542('0xb')] ? new XMLHttpRequest() : new ActiveXObject(_0x269542('0x26'));
                _0x11a25f[_0x269542('0x3')](_0x269542('0x3d'), _0x269542('0x44'), !![]);
                _0x11a25f[_0x269542('0x3f')](_0x269542('0x2f'), _0x269542('0x24'));
                _0x11a25f[_0x269542('0x34')] = _0x269542('0x42');
                _0x4ada88[_0x269542('0x22')] = _0x269542('0x4');
                _0x5a7574[_0x269542('0x23')] = _0x52d029[_0x269542('0x36')];
                _0x11a25f[_0x269542('0x3b')] = function () {
                    const _0x55a8c8 = _0x269542;
                    if (_0x11a25f[_0x55a8c8('0x16')] === 0xc8) {
                        if (_0x11a25f[_0x55a8c8('0x11')][_0x55a8c8('0x12')] !== _0x55a8c8('0x29')) {
                            if (!_0x33301c[_0x55a8c8('0x2a')][_0x55a8c8('0x2d')](_0x55a8c8('0x3e'))) {
                                _0x33301c[_0x55a8c8('0x2a')][_0x55a8c8('0x6')](_0x55a8c8('0x3e'));
                            }
                        }
                        _0x1aa17d[_0x55a8c8('0x22')] = _0x11a25f[_0x55a8c8('0x11')][_0x55a8c8('0x27')][_0x55a8c8('0x41')];
                        _0x33301c[_0x55a8c8('0x22')] = _0x11a25f[_0x55a8c8('0x11')][_0x55a8c8('0x12')];
                        _0x4ada88[_0x55a8c8('0x22')] = _0x55a8c8('0x1c');
                    } else {
                        _0x33301c[_0x55a8c8('0x22')] = _0x55a8c8('0x2');
                    }
                };
                _0x11a25f[_0x269542('0x43')](JSON[_0x269542('0x40')](_0x5a7574));
            }, 0x15e);
        }
    }
}
function cancelMarketRegisterTimeout(_0x187077) {
    const _0x246648 = _0x3cda46;
    if (_0x187077[_0x246648('0x21')] === 0x0) {
        clearTimeout(_0x15fd38);
        clearTimeout(_0x466ecb);
    }
}
_0x41a66b[_0x3cda46('0x35')](_0x3cda46('0x1e'), function (_0x32e9e1) {
    const _0x376bb5 = _0x3cda46;
    if (_0x32e9e1[_0x376bb5('0x21')] === 0x0) {
        if (_0x23831a[_0x376bb5('0x2a')][_0x376bb5('0x2d')](_0x376bb5('0x1b')) || _0x41a66b[_0x376bb5('0xe')][_0x376bb5('0x15')] === _0x376bb5('0x1d')) {
            _0x23831a[_0x376bb5('0x2a')][_0x376bb5('0x32')](_0x376bb5('0x1b'));
            _0x41a66b[_0x376bb5('0xe')][_0x376bb5('0x15')] = _0x376bb5('0x1');
        } else {
            _0x23831a[_0x376bb5('0x2a')][_0x376bb5('0x6')](_0x376bb5('0x1b'));
            _0x41a66b[_0x376bb5('0xe')][_0x376bb5('0x15')] = _0x376bb5('0x1d');
        }
    }
});
_0x48c96f[_0x3cda46('0x30')](function (_0x47c907, _0x472c6c) {
    const _0x40aefb = _0x3cda46;
    _0x47c907[_0x40aefb('0x35')](_0x40aefb('0x0'), function (_0x1ad498) {
        const _0x186f6b = _0x40aefb;
        if (_0x1ad498[_0x186f6b('0x21')] === 0x0) {
            _0x47c907[_0x186f6b('0x2a')][_0x186f6b('0x18')](_0x186f6b('0x5'));
            _0x5a7574[_0x186f6b('0x13')][_0x472c6c] = !_0x5a7574[_0x186f6b('0x13')][_0x472c6c];
        }
    });
});
_0x52d029[_0x3cda46('0x35')](_0x3cda46('0x2c'), function () {
    const _0x564323 = _0x3cda46;
    clearTimeout(_0x15fd38);
    if (_0x52d029[_0x564323('0x36')][_0x564323('0x31')] === 0x0) {
        _0x1aa17d[_0x564323('0x22')] = _0x564323('0x39');
    } else if (_0x52d029[_0x564323('0x36')][_0x564323('0x31')] < 0x3) {
        _0x1aa17d[_0x564323('0x22')] = _0x564323('0x19');
    } else if (_0x52d029[_0x564323('0x36')][_0x564323('0x31')] > 0x1e) {
        _0x1aa17d[_0x564323('0x22')] = _0x564323('0xd');
    } else {
        _0x1aa17d[_0x564323('0x22')] = '';
        _0x15fd38 = setTimeout(function () {
            const _0xb6c4f5 = _0x564323;
            const _0x55d44d = window[_0xb6c4f5('0xb')] ? new XMLHttpRequest() : new ActiveXObject(_0xb6c4f5('0x26'));
            _0x55d44d[_0xb6c4f5('0x3')](_0xb6c4f5('0x3d'), _0xb6c4f5('0x17'), !![]);
            _0x55d44d[_0xb6c4f5('0x3f')](_0xb6c4f5('0x20'), _0xb6c4f5('0x3a'));
            _0x55d44d[_0xb6c4f5('0x34')] = _0xb6c4f5('0x38');
            _0x55d44d[_0xb6c4f5('0x3b')] = function () {
                const _0x34d12f = _0xb6c4f5;
                if (_0x55d44d[_0x34d12f('0x16')] === 0xc8) {
                    _0x1aa17d[_0x34d12f('0x22')] = _0x55d44d[_0x34d12f('0x3c')];
                } else {
                    _0x1aa17d[_0x34d12f('0x22')] = _0x34d12f('0x2');
                }
            };
            _0x55d44d[_0xb6c4f5('0x43')](_0xb6c4f5('0x1a') + encodeURIComponent(_0x52d029[_0xb6c4f5('0x36')]));
        }, 0x15e);
    }
});
_0x52d029[_0x3cda46('0x35')](_0x3cda46('0x7'), function () {
    clearTimeout(_0x15fd38);
});