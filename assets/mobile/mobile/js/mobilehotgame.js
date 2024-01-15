/*
 * ie兼容性处理,添加为数组添加includes 和 filter 方法
 * */
$(function () {
    SlotMg.init();
});

if (!Array.prototype.includes) {
    Array.prototype.includes = function (searchElement /*, fromIndex*/) {
        'use strict';
        if (this == null) {
            throw new TypeError('Array.prototype.includes called on null or undefined');
        }

        var O = Object(this);
        var len = parseInt(O.length, 10) || 0;
        if (len === 0) {
            return false;
        }
        var n = parseInt(arguments[1], 10) || 0;
        var k;
        if (n >= 0) {
            k = n;
        } else {
            k = len + n;
            if (k < 0) {
                k = 0;
            }
        }
        var currentElement;
        while (k < len) {
            currentElement = O[k];
            if (searchElement === currentElement ||
                (searchElement !== searchElement && currentElement !== currentElement)) { // NaN !== NaN
                return true;
            }
            k++;
        }
        return false;
    };
}


if (!Array.prototype.filter) {
    Array.prototype.filter = function (fun/*, thisArg*/) {
        'use strict';

        if (this === void 0 || this === null) {
            throw new TypeError();
        }

        var t = Object(this);
        var len = t.length >>> 0;
        if (typeof fun !== 'function') {
            throw new TypeError();
        }

        var res = [];
        var thisArg = arguments.length >= 2 ? arguments[1] : void 0;
        for (var i = 0; i < len; i++) {
            if (i in t) {
                var val = t[i];

                // NOTE: Technically this should Object.defineProperty at
                //       the next index, as push can be affected by
                //       properties on Object.prototype and Array.prototype.
                //       But that method's new, and collisions should be
                //       rare, so use the more-compatible alternative.
                if (fun.call(thisArg, val, i, t)) {
                    res.push(val);
                }
            }
        }

        return res;
    };
}

function arrayContain(array, values) {
    array = array || [];
    values = values || [];
    if (array.length < values.length) return false;
    var ret = 0;
    for (var i = 0; i < values.length; i++) {
        array.includes(values[i]) && ret++;

    }
    if (values.length === ret) return true;

    return false;
}

!(function (w) {
    var SlotMg = SlotMg || {};

    var isLogin = false,  //登入状态
        oldCategory = '',
        tpl = ['<dl id="{{id}}" class="game-info" data-json=\'{{json}}\'>',
            '<dt>',
            '<img class="lazy" {{lazyLoad}}="{{categoryPic}}{{pic}}" alt="">',
            '</dt>',
            '<dd class="gamename1">',
            '<div class="game-play game-play1 tc">',
            '{{linkPlay}}',
            '{{linkDemo}}',
            '</div>',
            '</dd>',
            '<dd class="gamename2">',
            '<h3>{{name}}</h3>',
            '</dd>',
            '{{jackPot}}</dl>'
        ].join('');


    var DtConfig = {
        gameUrl: $('#j-gameurl').val() || '',
        slotKey: $('#j-slotKey').val() || '',
        referWebsite: $('#j-referWebsite').val() || ''
    };

    // 游戏配置信息
    var GameConfig = {
        baseUrl: $('#j-baseUrl').val(),
        ptToken: $('#j-ptToken').val(),
        ntToken: $('#j-ntToken').val(),
        dtToken: $('#j-dtToken').val(),
        dtReferWebSite: $('#-dtReferWebSite').val(),
        userName: $('#j-username').val(),
        isLogin: $('#j-isLogin').val()
    };

    SlotMg.Reg = null;
    SlotMg.GameContainer = $('.js-hotgame');

    SlotMg.IsLazyLoad = false;
    SlotMg.DataList = [];
    SlotMg.DataUrl = {
        PT: ['/data/slot/mobile/pt.json?v=6600000012'],
        PTAISA: ['/data/slot/mobile/ptaisa.json?v=6600000012'],
        PNG: ['/data/slot/mobile/png.json?v=6600000012'],
        DT: ['/data/slot/mobile/dt.json?v=6600000012'],
        MGS: ['/data/slot/mobile/mg.json?v=6600000012'],
        QT: ['/data/slot/mobile/qt.json?v=6600000012'],
        NT: ['/data/slot/mobile/nt.json?v=6600000012'],
        XIN: ['/data/slot/mobile/ag.json?v=6600000012'],
        PTSW: ['/data/slot/mobile/ptsw.json?v=6600000012'],
        CQ9: ['/data/slot/pc/cq9.json?v=6600000012'],
        PG: ['/data/slot/pc/pg.json?v=6600000012']
    };

    SlotMg.ImgUrl = {
        PT: ['/images/ptgames/'],
        PTAISA: ['/images/ptgames/'],
        PNG: ['/images/pngames/'],
        DT: ['/images/dtgames/'],
        MGS: ['/images/mggames/'],
        QT: ['/images/qtgames/'],
        NT: ['/images/ntgames/'],
        XIN: ['/images/xingames/'],
        MwG: ['/images/chessgames/'],
        PTSW: ['/images/ptswgames/'],
        CQ9: ['/images/cq9swgames/'],
        PG: ['/images/pggames/']
    };


    SlotMg.isIOS = function () {

        var u = navigator.userAgent;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        return isiOS;
    };

    SlotMg.init = function () {
        isLogin = $('#j-isLogin').val() === 'true';


        $(document).on('click', '.j-login', function () {
            if (!isLogin) {
                alert('请先登入账户！！');
                openLoginModule();
                return false;
            }
        });

        for (var key in SlotMg.DataUrl) {
            urls = SlotMg.DataUrl[key];

            $.getJSON(urls, function (data) {
                SlotMg.DataList = data;

            }).done(function () {
                SlotMg.builHtml(SlotMg.DataList);
            });

        }

        //正式游戏
        $(document).on('click', '.game-info .btn-play', function () {
            if (isLogin) {
                var $that = $(this),
                    obj = $that.closest('.game-info').data('json');

                mobileManage.getLoader().open("进入游戏中");
                setTimeout(function () {
                    SlotMg.linkAction(obj, 0);
                }, 200);
            }
            return false;
        });

        //试玩事件处理
        $(document).on('click', '.game-info .btn-try', function () {
            var $that = $(this),
                obj = $that.closest('.game-info').data('json');
            console.log(obj)
            mobileManage.getLoader().open("进入游戏中");
            setTimeout(function () {
                SlotMg.linkAction(obj, 1);
            }, 200);
        });

    };


    /**
     * 获取查询条件返回数组的形式
     */
    SlotMg.getWhere = function (arr) {
        var ret = [];
        for (var p in arr) {
            if (arr[p]) {
                if (p == 'tag') {
                    arr[p].length > 0
                    && ret.push('SlotMg.Reg.test(","+el.' + p + '.join(","))');
                    //ret.push('el.'+p+'.includes("'+FilterObj[p]+'")');
                } else if (p == 'line') {
                    ret.push('SlotMg.LineCp("' + arr['line'] + '",el.' + p + ')');
                } else {
                    ret.push('el.' + p + '=="' + arr[p] + '"');
                }
            }
        }
        return ret;
    };
    /**
     * 判断是否在集合范围
     * @param rang
     * @param val
     * @returns {boolean}
     * @constructor
     */
    SlotMg.LineCp = function (rang, val) {
        if (val != "" || typeof val != "undefined") {
            val = parseInt(val);
            var r = rang.split('-'),
                start = parseInt(r[0]),
                end = r[1] || '';

            if (end) {
                if (start <= val && val <= end) {
                    return true;
                }
            } else {
                if (start <= val) {
                    return true;
                }
            }
            return false;
        }
    };

    /**
     * 多条件查找游戏获取游戏
     */
    SlotMg.showGames = function () {

        // 替換分類
        var filter = SlotMg.setFilter();
        var category = filter.category;

        if (category != "" && typeof category != "undefined") {

            if (category == "AG") {
                return false;
            }

            if (category == "PT" && ptClient != 1) {

                var isiOS = SlotMg.isIOS();

                if (isiOS) {
                    SlotMg.ptClient["state"] = 0;
                }

                SlotMg.DataList.unshift(SlotMg.ptClient);
                ptClient = 1;
            }

            if (category == "DT" && dtClient != 1) {

                var isiOS = SlotMg.isIOS();

                if (isiOS) {
                    SlotMg.dtClient["href"] = "http://down.dreamtech.asia/QY8/ios.html";
                } else {
                    SlotMg.dtClient["href"] = "http://down.dreamtech.asia/QY8/android.html";
                }

                SlotMg.DataList.unshift(SlotMg.dtClient);
                dtClient = 1;
            }

            setCookie("slotgame", category);
        }

        SlotMg.getByCategory(filter.category, function () {
            var whereArr = SlotMg.getWhere(filter);
            if (whereArr.length) {
                var _funStr = ' return ' + whereArr.join(' && ');
                var _tmpFun = new Function("el", _funStr);  // 根据动态生成查询条件,动态生成方法
                var _d = SlotMg.DataList.filter(_tmpFun);
                SlotMg.builHtml(_d);
            } else {
                // SlotMg.builHtml(SlotMg.DataList.slice(0, 200));// 最多只获取100个数据
                SlotMg.builHtml(SlotMg.DataList);
            }
            SlotMg.setCollectState();
        });
    };


    SlotMg.setActiveClass = function ($ele) {
        var id = $ele.attr("id");
        if (id == "aggame") {
            return false;
        }

        var $target = $ele;
        if ($target.parent().hasClass('filter_item')) {
            $target = $ele.parent();
        }
        $target.addClass('active').siblings().removeClass('active');
    };


    /**
     * 获取随机数
     * @param min 开始的数
     * @param max 结束的数
     * @param int 小数点位数
     * @returns {string}
     */
    SlotMg.getRandom = function (min, max, int) {
        var ret = Math.random() * (max - min) + min;
        int = int || 0;

        return ret.toFixed(int);
    };


    SlotMg.linkAction = function (obj, isFun) {
        //正式
        if (isFun == "0") {
            switch (obj.category) {
                case 'PT':
                    window.location.href = '/gamePTSlotH5Login.php?language=zh-cn&gameCode=' + obj.id;
                    break;
                case 'PTAISA':
                    window.location.href = '/app/ptAsiaH5Login.php?gameCode=' + obj.id;
                    break;
                case 'MGS':
                    var subType = obj.subType;
                    var clientid = obj.clientid;
                    var productid = obj.productid;

                    $.post('/mobi/gameH5MGS.php', {
                        gameCode: obj.id,
                        fromApp: 'app',
                        subType: subType,
                        clientid: clientid,
                        productid: productid
                    }, function (result) {
                        if (result.success) {
                            window.location.href = result.data.url;
                        } else {
                            mobileManage.getLoader().close();
                            alert(result.message);
                        }
                    });
                    break;
                case 'DT':
                    if (obj.type == 'DEM') {
                        alert('正式游戏，敬请期待!');  //判断状态是否为试玩
                        mobileManage.getLoader().close();
                    } else {
                        window.location.href = "/game/gameLoginDT.php?isfun=0&gameCode=" + obj.id + "&language=zh_CN&clientType=1";
                    }
                    break;
                case 'PNG':
                    var gid = obj.id;
                    $.post('/gamePNGFlashForTp.php', {practice: 0, gameCode: gid, fromApp: 'app'}, function (result) {
                        if (result.success) {
                            window.location.href = result.data.url;
                        } else {
                            mobileManage.getLoader().close();
                            alert(result.message);
                        }
                    });
                    break;
                case 'QT':
                    SlotMg.load_qtgame(obj.id, isFun, 'qtGames', window.location.href + '?showType=QT');
                    break;
                case 'NT':
                    var ntUrl = 'https://yx678.load.xamahaha.com/disk2/netent?game={0}&language=cn&lobbyUrl={1}&key={2}&mobile=true';
                    if (GameConfig.ntToken) {
                        window.location.href = String.format(ntUrl, obj.gid, window.location.href, GameConfig.ntToken);
                    } else {
                        $.post('/mobi/getNTGame.php', {gameCode: obj.gid}, function (result) {
                            if (result.success) {
                                window.location.href = String.format(ntUrl, obj.gid, window.location.href, result.message);
                            } else {
                                mobileManage.getLoader().close();
                                alert(result.message);
                            }
                        });
                    }
                    break;
                case 'XIN':
                    var gameUri = '/loginAgSlot.php?gameType={{id}}&lang=zh-cn&deviceType=mobile';
                    gameUri = gameUri.replace(/\{\{id\}\}/g, obj.id);
                    window.location.href = gameUri;
                    break;
                case 'PTSW':
                    window.location.href = "/game/gameLoginPtSky.php?mode=real&gameCode=" + obj.id + "&lobby=" + window.location.host + "/mobile/app/gameLobby.jsp";
                    break;
                case 'CQ9':
                    window.location.href = 'game/cq9Login.php?gameCode=' + obj.id + '&type=MB';
                    break;
                case 'PG':
                    window.location.href = 'game/pgLogin.php?gameCode=' + obj.code + '&type=MB';
                    break;
            }
        } else {
            //试玩
            switch (obj.category) {
                case 'PT':
                    window.location.href = '/gamePTSlotH5Login.php?language=zh-cn&gameCode=' + obj.id;
                    break;
                case 'PTAISA':
                    window.location.href = '/app/ptAsiaH5Login.php?gameCode=' + obj.id;
                    break;
                case 'MGS':
                    var gameUri;
                    if (obj.subType == '3RD') {
                        gameUri = 'https://redirector3.valueactive.eu/Casino/Default.aspx?serverid=1866&applicationID=7217&ModuleID=19493&ul=zh&siteID=TNG&playmode=demo&gameid={{id}}&clientid={{clientid}}&productid={{productid}}&lobbyurl={{lobbyurl}}'.replace(/\{\{id\}\}/g, obj.id).replace(/\{\{clientid\}\}/g, obj.clientid).replace(/\{\{productid\}\}/g, obj.productid).replace(/\{\{lobbyurl\}\}/g, window.location.href);
                    } else {
                        gameUri = "https://mobile22.gameassists.co.uk/MobileWebServices_40/casino/game/launch/QY8com/" + obj.code + "/zh-cn?loginType=VanguardSessionToken&isPracticePlay=true&casinoId=2712&isRGI=true&authToken=&lobbyurl=" + window.location.href + "/mobile/app/gameLobby.jsp?showType=MGS";
                    }
                    window.location.href = gameUri;
                    break;
                case 'DT':
                    window.location.href = 'https://play.dreamtech8.com/playSlot.aspx?gameCode=' + obj.id + '&isfun=1&type=dt&language=zh_CN';
                    break;
                case 'PNG':
                    var gameUri = "https://bsicw.playngonetwork.com/casino/PlayMobile?pid={{pid}}&gid={{gid}}&lang=zh_CN&practice=1";
                    gameUri = gameUri.replace(/\{\{pid\}\}/g, obj.code)
                        .replace(/\{\{gid\}\}/g, obj.id);
                    window.location.href = gameUri;
                    break;
                case 'QT':
                    SlotMg.load_qtgame(obj.id, isFun, 'qtGames', window.location.href + '?showType=QT');
                    break;
                case 'NT':
                    if (obj.subType == 'NOTEST') {
                        alert('暂时不支持手机试玩，敬请期待!');  //判断状态是否为试玩
                    } else {
                        var ntUrl = 'https://netent-static.casinomodule.com/games/{0}/game/{0}.xhtml?lobbyURL=https%3A%2F%2Fwww.netent.com%2Fen%2Fsection%2Fentertain%2F&server=https%3A%2F%2Fnetent-game.casinomodule.com%2F&sessId=DEMO1499112425678-1903-EUR&operatorId=default&gameId={0}&lang=en&integration=standard&keepAliveURL=&targetElement=game&flashParams.bgcolor=000000&gameName={0}e&staticServer=https%3A%2F%2Fnetent-static.casinomodule.com%2F&language=cn&lobbyUrl={1}';
                        window.location.href = String.format(ntUrl, obj.gid, window.location.href + '?showType=NT');

                        break;
                    }
                    break;
                case 'XIN':
                    var gameUri = '/asp/agTryLogin.php?gameType={{id}}&lang=zh-cn&deviceType=mobile';
                    gameUri = gameUri.replace(/\{\{id\}\}/g, obj.id);
                    window.location.href = gameUri;
                    break;
                case 'PTSW':
                    window.location.href = "/game/gameLoginPtSky.php?mode=fun&gameCode=" + obj.id + "&lobby=" + window.location.host + "/mobile/app/gameLobby.jsp";
                    break;
                case 'CQ9':
                    _tmp = 'javascript:;';
                    break;
                case 'PG':
                    window.location.href = 'game/pgTryGame.php?gameCode=' + obj.code + '&type=MB';
                    break;
            }
        }

        mobileManage.getLoader().close();
    };


    SlotMg.load_qtgame = function (gameCode, isfun, gameType, origin) {

        $.post('/gameQTForTp.php', {
                gameCode: gameCode,
                isfun: isfun,
                gameType: gameType,
                origin: origin,
                fromApp: 'app'
            },
            function (result) {
                if (result.success) {
                    window.location.href = result.data.url;
                } else {
                    alert(result.message);
                    mobileManage.getLoader().close();
                }
            });
    };

    /**
     * 获取试玩连接
     * @param obj
     * @returns {string}D
     */
    SlotMg.getLinkDemo = function (obj) {

        if (typeof obj.href != "undefined") {
            return "";
        }

        // pt 没有试玩游戏
        if (obj.category == 'PT') {
            return "";
        }

        // nt 十款没有试玩游戏
        if (obj.category == 'NT' && obj.subType == "NOTEST") {
            return "";
        }

        // mg 奖池游戏没有试玩游戏
        if (obj.category == 'MGS' && obj.subType == "PRIZE") {
            return "";
        }

        return '<div class="o-btn btn-try">免费试玩</div>';
    };

    /**
     * 获取进入游戏连接
     * @param obj
     * @returns {string}
     */
    SlotMg.getLinkPlay = function () {
        return '<div class="j-login o-btn btn-play">立刻游戏</div>';
    };


    SlotMg.builHtml = function (data) {
        var _ret = [],
            animaClass = '',
            num = 0;
        category = data[0].category.toLowerCase();

        if (category == "sw") {
            category = 'ptsw';
        }

        var isiOS = SlotMg.isIOS();

        $.each(data, function (i, o) {
            if (num > 2) {
                return false;
            }

            var html = "";

            // 遊戲總開關
            if (o.state == 0) {
                return true;
            }

            // DT DT-2 不支持手機
            if (o.category == 'DT' && o.subType != 'DT-2') {
                return true;
            }

            if (o.category == 'SW') {
                o.category = "PTSW";
            }
            // NT noIOS 不支持IOS
            if (isiOS && o.category == 'NT' && o.subType == 'noIOS') {
                return true;
            }

            var jackpot = "";
            if (o.category == 'DT' && o.jackpot == 1) {
                jackpot = "<i class='jackpot-flag'></i>";
            } else {
                jackpot = "";
            }

            _ret.push(tpl.replace(/\{\{pic\}\}/g, o.pic)
                .replace(/\{\{name\}\}/g, o.name)
                .replace(/\{\{id\}\}/g, o.id)
                .replace(/\{\{class\}\}/g, animaClass)
                .replace(/\{\{categoryPic\}\}/g, SlotMg.ImgUrl[o.category])
                .replace(/\{\{key\}\}/g, '')
                .replace(/\{\{eName\}\}/g, o.eName || '')
                .replace(/\{\{json\}\}/g, JSON.stringify(o))
                .replace(/\{\{subType\}\}/g, o.subType)
                .replace(/\{\{lazyLoad\}\}/g, SlotMg.IsLazyLoad ? 'data-original' : 'src')
                .replace(/\{\{linkDemo\}\}/g, SlotMg.getLinkDemo(o))
                .replace(/\{\{linkPlay\}\}/g, SlotMg.getLinkPlay(o))
                .replace(/\{\{jackPot\}\}/g, jackpot));

            num++;

        });


        $('.item.' + category).html(_ret);
        SlotMg.lazyload();
    };

    SlotMg.lazyload = function () {
        if (SlotMg.IsLazyLoad) {
            $('img.lazy').lazyload();
        }
    };

    w.SlotMg = SlotMg;
})(window);

/**
 * 工具函数
 */
!function (window, $) {
    var Util = window.Util || {};

    /**
     * 获取url参数值
     * @param name
     * @param url
     * @returns {*}
     */
    Util.getQueryString = function (name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    root = typeof exports !== "undefined" && exports !== null ? exports : window;

    root.Util = Util;
}(window);

$(function () {


    $(".menu_item").on("click", function () {
        var $target = $(this);
        var ind = $(this).index();
        $target.addClass('active').siblings().removeClass('active');
        $(".item-game").eq(ind).addClass('active').siblings().removeClass('active');
    });


});
