const _0x5515 = [
    'classList',
    '#subscribeButtonCont',
    'Unsubscribed!',
    'openedSideNav',
    'send',
    'remove',
    'addEventListener',
    '#subscriberCount',
    'Subscribed!',
    'top',
    'open',
    '#notificationCont',
    'responseType',
    'response',
    '#menuToggle',
    'button',
    '#E60505',
    'buttonText',
    'add',
    '\x22\x20onmouseup=\x22toggleSubscribe(event)\x22\x20onmousedown=\x22cancelToggleSubscribeTimeout(event)\x22>',
    'pointer',
    '<button\x20id=\x22subscribeButton\x22\x20class=\x22',
    'filter',
    '-10vh',
    'animationName',
    'id=',
    '</button>',
    'subscribe.php',
    'style',
    'onload',
    'notificationColor',
    'onerror',
    'XMLHttpRequest',
    'subscriberCount',
    'click',
    'brightness(100%)',
    'menuAnimationOpen',
    'notificationText',
    'json',
    'application/x-www-form-urlencoded',
    'Microsoft.XMLHTTP',
    'querySelector',
    'buttonClass',
    'menuAnimationClose',
    'Content-Type',
    '#notificationText',
    'contains',
    'location',
    'innerHTML',
    'setRequestHeader',
    'POST',
    'search',
    'An\x20error\x20occurred.',
    'backgroundColor',
    '<div\x20id=\x22loadingImageCont\x22></div>',
    '#sidenav',
    'status',
    'cursor'
];
(function (_0x114fe3, _0x551505) {
    const _0x1bd108 = function (_0x3bd026) {
        while (--_0x3bd026) {
            _0x114fe3['push'](_0x114fe3['shift']());
        }
    };
    _0x1bd108(++_0x551505);
}(_0x5515, 0x12a));
const _0x1bd1 = function (_0x114fe3, _0x551505) {
    _0x114fe3 = _0x114fe3 - 0x0;
    let _0x1bd108 = _0x5515[_0x114fe3];
    return _0x1bd108;
};
const _0x2f607e = _0x1bd1;
'use strict';
const _0x3bd026 = document[_0x2f607e('0x21')](_0x2f607e('0x6'));
const _0x162654 = document[_0x2f607e('0x21')](_0x2f607e('0x2f'));
const _0x12943c = document[_0x2f607e('0x21')](_0x2f607e('0x3'));
const _0x3a38e1 = document[_0x2f607e('0x21')](_0x2f607e('0x25'));
var _0x140cd9;
_0x3bd026[_0x2f607e('0x14')][_0x2f607e('0xe')] = _0x2f607e('0x1b');
_0x3bd026[_0x2f607e('0x14')][_0x2f607e('0x31')] = _0x2f607e('0xc');
_0x3bd026[_0x2f607e('0x38')](_0x2f607e('0x1a'), function (_0x3ef765) {
    const _0x2805d7 = _0x2f607e;
    if (_0x3ef765[_0x2805d7('0x7')] === 0x0) {
        if (_0x162654[_0x2805d7('0x32')][_0x2805d7('0x26')](_0x2805d7('0x35')) || _0x3bd026[_0x2805d7('0x14')][_0x2805d7('0x10')] === _0x2805d7('0x1c')) {
            _0x162654[_0x2805d7('0x32')][_0x2805d7('0x37')](_0x2805d7('0x35'));
            _0x3bd026[_0x2805d7('0x14')][_0x2805d7('0x10')] = _0x2805d7('0x23');
        } else {
            _0x162654[_0x2805d7('0x32')][_0x2805d7('0xa')](_0x2805d7('0x35'));
            _0x3bd026[_0x2805d7('0x14')][_0x2805d7('0x10')] = _0x2805d7('0x1c');
        }
    }
});
function toggleSubscribe(_0x568f8e) {
    const _0x4098f2 = _0x2f607e;
    const _0x1d39cd = document[_0x4098f2('0x21')](_0x4098f2('0x33'));
    if (_0x568f8e[_0x4098f2('0x7')] === 0x0) {
        clearTimeout(_0x140cd9);
        _0x140cd9 = setTimeout(function () {
            const _0x4c588b = _0x4098f2;
            const _0x1637b1 = window[_0x4c588b('0x18')] ? new XMLHttpRequest() : new ActiveXObject(_0x4c588b('0x20'));
            const _0x4d99f5 = new URLSearchParams(window[_0x4c588b('0x27')][_0x4c588b('0x2b')]);
            _0x1637b1[_0x4c588b('0x2')](_0x4c588b('0x2a'), _0x4c588b('0x13'), !![]);
            _0x1637b1[_0x4c588b('0x29')](_0x4c588b('0x24'), _0x4c588b('0x1f'));
            _0x1637b1[_0x4c588b('0x4')] = _0x4c588b('0x1e');
            _0x1d39cd[_0x4c588b('0x28')] = _0x4c588b('0x2e');
            _0x1637b1[_0x4c588b('0x17')] = function () {
                const _0x32a1fc = _0x4c588b;
                _0x12943c[_0x32a1fc('0x14')][_0x32a1fc('0x2d')] = _0x32a1fc('0x8');
                _0x3a38e1[_0x32a1fc('0x28')] = _0x32a1fc('0x2c');
                setTimeout(function () {
                    const _0x24d37e = _0x32a1fc;
                    _0x12943c[_0x24d37e('0x14')][_0x24d37e('0x1')] = _0x24d37e('0xf');
                }, 0x3e8);
            };
            _0x1637b1[_0x4c588b('0x15')] = function () {
                const _0x3b1c6d = _0x4c588b;
                if (_0x1637b1[_0x3b1c6d('0x30')] === 0xc8) {
                    const _0x2e4335 = _0x1637b1[_0x3b1c6d('0x5')][_0x3b1c6d('0x16')];
                    const _0x29cbbe = _0x1637b1[_0x3b1c6d('0x5')][_0x3b1c6d('0x1d')];
                    _0x12943c[_0x3b1c6d('0x14')][_0x3b1c6d('0x1')] = 0x0;
                    _0x12943c[_0x3b1c6d('0x14')][_0x3b1c6d('0x2d')] = _0x2e4335;
                    _0x3a38e1[_0x3b1c6d('0x28')] = _0x29cbbe;
                    _0x1d39cd[_0x3b1c6d('0x28')] = _0x3b1c6d('0xd') + _0x1637b1[_0x3b1c6d('0x5')][_0x3b1c6d('0x22')] + _0x3b1c6d('0xb') + _0x1637b1[_0x3b1c6d('0x5')][_0x3b1c6d('0x9')] + _0x3b1c6d('0x12');
                    setTimeout(function () {
                        const _0x541fd7 = _0x3b1c6d;
                        _0x12943c[_0x541fd7('0x14')][_0x541fd7('0x1')] = _0x541fd7('0xf');
                    }, 0x3e8);
                    if (_0x1637b1[_0x3b1c6d('0x5')][_0x3b1c6d('0x1d')] === _0x3b1c6d('0x0') || _0x1637b1[_0x3b1c6d('0x5')][_0x3b1c6d('0x1d')] === _0x3b1c6d('0x34')) {
                        const _0x10f246 = document[_0x3b1c6d('0x21')](_0x3b1c6d('0x39'));
                        _0x10f246[_0x3b1c6d('0x28')] = _0x1637b1[_0x3b1c6d('0x5')][_0x3b1c6d('0x19')];
                    }
                } else {
                    _0x12943c[_0x3b1c6d('0x14')][_0x3b1c6d('0x1')] = 0x0;
                    _0x12943c[_0x3b1c6d('0x14')][_0x3b1c6d('0x2d')] = _0x3b1c6d('0x8');
                    _0x3a38e1[_0x3b1c6d('0x28')] = _0x3b1c6d('0x2c');
                    setTimeout(function () {
                        const _0x37b6b2 = _0x3b1c6d;
                        _0x12943c[_0x37b6b2('0x14')][_0x37b6b2('0x1')] = _0x37b6b2('0xf');
                    }, 0x3e8);
                }
            };
            _0x1637b1[_0x4c588b('0x36')](_0x4c588b('0x11') + encodeURIComponent(_0x4d99f5['id']));
        }, 0x15e);
    }
}
function cancelToggleSubscribeTimeout(_0x2d39ce) {
    const _0x12f4c7 = _0x2f607e;
    if (_0x2d39ce[_0x12f4c7('0x7')] === 0x0) {
        clearTimeout(_0x140cd9);
    }
}