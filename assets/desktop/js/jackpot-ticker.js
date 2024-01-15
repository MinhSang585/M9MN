(function () {
    "use strict";
    var ticker6 = new Ticker({info: 2, casino: 'happypenguin88', currency: 'CNY'});
    ticker6.attachToTextBox('text6');
    ticker6.SetCurrencySign('');
    ticker6.SetCurrencyPos(1);
    ticker6.tick();
})();