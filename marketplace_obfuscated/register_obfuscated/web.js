const _0x43b1 = [
    'keydown',
    'games',
    'onload',
    'automotive',
    'marketname=',
    'groceries',
    'classList',
    'household',
    '.marketCategoryBox',
    'CDandVinyl',
    'XMLHttpRequest',
    'indexOf',
    'Market\x20name\x20must\x20contain\x20at\x20least\x203\x20characters.',
    'brightness(100%)',
    'openedSideNav',
    'innerHTML',
    'menuAnimationClose',
    'value',
    'filter',
    'musicalInstruments',
    'message',
    'marketCategories',
    'Content-type',
    '<div\x20id=\x22loadingImageCont\x22></div>',
    '#marketRegisterButton',
    'gardening',
    'Content-Type',
    'books',
    'length',
    'trim',
    '#marketNameError',
    'addEventListener',
    'responseText',
    '#registerMessage',
    'An\x20error\x20occurred.',
    'kitchenAndDining',
    'outdoorsAndSports',
    '#marketNameField',
    '<button\x20id=\x22marketRegisterButton\x22\x20onmouseup=\x22submitMarketRegister(event)\x22\x20onmousedown=\x22cancelMarketRegisterTimeout(event)\x22>Register</button>',
    'json',
    'response',
    'babyCare',
    'tickedCategoryBox',
    'officeSupplies',
    'button',
    'marketName',
    'toys',
    'stringify',
    'status',
    'push',
    'POST',
    '#marketRegisterButtonCont',
    'clothesAndAccessories',
    'add',
    'keyup',
    'beauty',
    'personalCare',
    'Market\x20name\x20has\x20to\x20be\x20under\x2030\x20characters.',
    'marketNameError',
    'style',
    'marketNameTaken.php',
    'querySelector',
    'health',
    'petSupplies',
    'tools',
    'onerror',
    'travelSupplies',
    'querySelectorAll',
    'application/x-www-form-urlencoded',
    'inputErrorText',
    'This\x20field\x20is\x20required.',
    'moviesAndTV',
    'Microsoft.XMLHTTP',
    'responseType',
    'click',
    '#menuToggle',
    'Please\x20select\x20at\x20least\x20one\x20category.',
    'contains',
    'menuAnimationOpen',
    'electronics',
    'open',
    'Success!',
    'software',
    'setRequestHeader',
    'pointer',
    'remove',
    'toggle',
    'registerMarketplace.php',
    '#sidenav',
    'application/json',
    'splice',
    'animationName',
    'text',
    'send',
    'cursor',
    'mouseup',
    'forEach'
];
(function (_0x5e1a96, _0x2d4de1) {
    const _0x43b1f2 = function (_0x4d1ca1) {
        while (--_0x4d1ca1) {
            _0x5e1a96['push'](_0x5e1a96['shift']());
        }
    };
    _0x43b1f2(++_0x2d4de1);
}(_0x43b1, 0xb9));
const _0x4d1c = function (_0x5e1a96, _0x2d4de1) {
    _0x5e1a96 = _0x5e1a96 - 0xf6;
    let _0x43b1f2 = _0x43b1[_0x5e1a96];
    return _0x43b1f2;
};
const _0xad9d58 = _0x4d1c;
'use strict';
const _0xc14d32 = document[_0xad9d58(0x13c)](_0xad9d58(0x14a));
const _0x149208 = document[_0xad9d58(0x13c)](_0xad9d58(0xf6));
const _0x4272b9 = document[_0xad9d58(0x142)](_0xad9d58(0x107));
const _0x153b67 = document[_0xad9d58(0x13c)](_0xad9d58(0x124));
const _0x142e47 = document[_0xad9d58(0x13c)](_0xad9d58(0x11d));
const _0xefc73e = document[_0xad9d58(0x13c)](_0xad9d58(0x117));
const _0x5142a0 = document[_0xad9d58(0x13c)](_0xad9d58(0x120));
const _0x57cbac = document[_0xad9d58(0x13c)](_0xad9d58(0x132));
var _0x125972;
var _0x3159c0;
const _0x45beeb = [
    _0xad9d58(0x102),
    _0xad9d58(0x128),
    _0xad9d58(0x11a),
    _0xad9d58(0x108),
    _0xad9d58(0x133),
    _0xad9d58(0x14e),
    _0xad9d58(0x118),
    _0xad9d58(0x123),
    _0xad9d58(0x104),
    _0xad9d58(0x13d),
    _0xad9d58(0x106),
    _0xad9d58(0x137),
    _0xad9d58(0x122),
    _0xad9d58(0x141),
    _0xad9d58(0x136),
    _0xad9d58(0x146),
    _0xad9d58(0x112),
    _0xad9d58(0x12a),
    _0xad9d58(0x13e),
    _0xad9d58(0x151),
    _0xad9d58(0x13f),
    _0xad9d58(0x12d),
    _0xad9d58(0x100)
];
var _0x1089af = {
    'marketName': '',
    'marketCategories': []
};
_0xc14d32[_0xad9d58(0x13a)][_0xad9d58(0x111)] = _0xad9d58(0x10c);
_0xc14d32[_0xad9d58(0x13a)][_0xad9d58(0xfc)] = _0xad9d58(0x153);
function submitMarketRegister(_0x149f76) {
    const _0x930022 = _0xad9d58;
    if (_0x149f76[_0x930022(0x12b)] === 0x0) {
        clearTimeout(_0x125972);
        clearTimeout(_0x3159c0);
        if (_0x153b67[_0x930022(0x110)][_0x930022(0x11b)] > 0x3 && _0x153b67[_0x930022(0x110)][_0x930022(0x11b)] < 0x1e && _0x1089af[_0x930022(0x114)][_0x930022(0x11b)] > 0x0) {
            _0x125972 = setTimeout(function () {
                const _0x3924ad = _0x930022;
                const _0x73f6f4 = window[_0x3924ad(0x109)] ? new XMLHttpRequest() : new ActiveXObject(_0x3924ad(0x147));
                _0x73f6f4[_0x3924ad(0x14f)](_0x3924ad(0x131), _0x3924ad(0x156), !![]);
                _0x73f6f4[_0x3924ad(0x152)](_0x3924ad(0x119), _0x3924ad(0xf7));
                _0x73f6f4[_0x3924ad(0x148)] = _0x3924ad(0x126);
                _0x57cbac[_0x3924ad(0x10e)] = _0x3924ad(0x116);
                _0x1089af[_0x3924ad(0x12c)] = _0x153b67[_0x3924ad(0x110)];
                _0x73f6f4[_0x3924ad(0x101)] = function () {
                    const _0xb9de99 = _0x3924ad;
                    if (_0x73f6f4[_0xb9de99(0x12f)] === 0xc8) {
                        if (_0x73f6f4[_0xb9de99(0x127)][_0xb9de99(0x113)] === _0xb9de99(0x150)) {
                            if (_0x5142a0[_0xb9de99(0x105)][_0xb9de99(0x14c)](_0xb9de99(0x144))) {
                                _0x5142a0[_0xb9de99(0x105)][_0xb9de99(0x154)](_0xb9de99(0x144));
                            }
                        }
                        _0x142e47[_0xb9de99(0x10e)] = _0x73f6f4[_0xb9de99(0x127)][_0xb9de99(0x139)];
                        _0x5142a0[_0xb9de99(0x10e)] = _0x73f6f4[_0xb9de99(0x127)][_0xb9de99(0x113)];
                        _0x57cbac[_0xb9de99(0x10e)] = _0xb9de99(0x125);
                    } else {
                        _0x5142a0[_0xb9de99(0x10e)] = _0xb9de99(0x121);
                    }
                };
                _0x73f6f4[_0x3924ad(0xfb)](JSON[_0x3924ad(0x12e)](_0x1089af));
            }, 0x15e);
        }
    }
}
function cancelMarketRegisterTimeout(_0x5b55d2) {
    const _0x500f21 = _0xad9d58;
    if (_0x5b55d2[_0x500f21(0x12b)] === 0x0) {
        clearTimeout(_0x125972);
        clearTimeout(_0x3159c0);
    }
}
_0xc14d32[_0xad9d58(0x11e)](_0xad9d58(0x149), function () {
    const _0xc20816 = _0xad9d58;
    if (_0x149208[_0xc20816(0x105)][_0xc20816(0x14c)](_0xc20816(0x10d)) || _0xc14d32[_0xc20816(0x13a)][_0xc20816(0xf9)] === _0xc20816(0x14d)) {
        _0x149208[_0xc20816(0x105)][_0xc20816(0x154)](_0xc20816(0x10d));
        _0xc14d32[_0xc20816(0x13a)][_0xc20816(0xf9)] = _0xc20816(0x10f);
    } else {
        _0x149208[_0xc20816(0x105)][_0xc20816(0x134)](_0xc20816(0x10d));
        _0xc14d32[_0xc20816(0x13a)][_0xc20816(0xf9)] = _0xc20816(0x14d);
    }
});
_0x4272b9[_0xad9d58(0xfe)](function (_0x518924, _0x4b9953) {
    const _0x5b9d6a = _0xad9d58;
    _0x518924[_0x5b9d6a(0x11e)](_0x5b9d6a(0xfd), function (_0x103d98) {
        const _0x5b9e99 = _0x5b9d6a;
        if (_0x103d98[_0x5b9e99(0x12b)] === 0x0) {
            const _0xbf0ecd = _0x1089af[_0x5b9e99(0x114)][_0x5b9e99(0x10a)](_0x45beeb[_0x4b9953]);
            if (_0xbf0ecd === -0x1) {
                _0x1089af[_0x5b9e99(0x114)][_0x5b9e99(0x130)](_0x45beeb[_0x4b9953]);
            } else {
                _0x1089af[_0x5b9e99(0x114)][_0x5b9e99(0xf8)](_0xbf0ecd, 0x1);
            }
            _0x518924[_0x5b9e99(0x105)][_0x5b9e99(0x155)](_0x5b9e99(0x129));
            if (_0x1089af[_0x5b9e99(0x114)][_0x5b9e99(0x11b)] === 0x0) {
                if (!_0x5142a0[_0x5b9e99(0x105)][_0x5b9e99(0x14c)](_0x5b9e99(0x144))) {
                    _0x5142a0[_0x5b9e99(0x105)][_0x5b9e99(0x134)](_0x5b9e99(0x144));
                }
                _0x5142a0[_0x5b9e99(0x10e)] = _0x5b9e99(0x14b);
            } else {
                _0x5142a0[_0x5b9e99(0x10e)] = '';
            }
        }
    });
});
_0x153b67[_0xad9d58(0x11e)](_0xad9d58(0x135), function () {
    const _0x276bfc = _0xad9d58;
    clearTimeout(_0x125972);
    if (_0x153b67[_0x276bfc(0x110)][_0x276bfc(0x11b)][_0x276bfc(0x11c)]() === 0x0) {
        _0x142e47[_0x276bfc(0x10e)] = _0x276bfc(0x145);
    } else if (_0x153b67[_0x276bfc(0x110)][_0x276bfc(0x11b)] < 0x3) {
        _0x142e47[_0x276bfc(0x10e)] = _0x276bfc(0x10b);
    } else if (_0x153b67[_0x276bfc(0x110)][_0x276bfc(0x11b)] > 0x1e) {
        _0x142e47[_0x276bfc(0x10e)] = _0x276bfc(0x138);
    } else {
        _0x142e47[_0x276bfc(0x10e)] = '';
        _0x125972 = setTimeout(function () {
            const _0x3beb2d = _0x276bfc;
            const _0x3ce853 = window[_0x3beb2d(0x109)] ? new XMLHttpRequest() : new ActiveXObject(_0x3beb2d(0x147));
            _0x3ce853[_0x3beb2d(0x14f)](_0x3beb2d(0x131), _0x3beb2d(0x13b), !![]);
            _0x3ce853[_0x3beb2d(0x152)](_0x3beb2d(0x115), _0x3beb2d(0x143));
            _0x3ce853[_0x3beb2d(0x148)] = _0x3beb2d(0xfa);
            _0x3ce853[_0x3beb2d(0x140)] = function () {
                const _0x5b2b73 = _0x3beb2d;
                _0x142e47[_0x5b2b73(0x10e)] = _0x5b2b73(0x121);
            };
            _0x3ce853[_0x3beb2d(0x101)] = function () {
                const _0x19688e = _0x3beb2d;
                if (_0x3ce853[_0x19688e(0x12f)] === 0xc8) {
                    _0x142e47[_0x19688e(0x10e)] = _0x3ce853[_0x19688e(0x11f)];
                } else {
                    _0x142e47[_0x19688e(0x10e)] = _0x19688e(0x121);
                }
            };
            _0x3ce853[_0x3beb2d(0xfb)](_0x3beb2d(0x103) + encodeURIComponent(_0x153b67[_0x3beb2d(0x110)]));
        }, 0x15e);
    }
});
_0x153b67[_0xad9d58(0x11e)](_0xad9d58(0xff), function () {
    clearTimeout(_0x125972);
});