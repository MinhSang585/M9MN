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

    var $filter = $('#j-filter'),
        $filterBtn = $filter.find('a'),
        $resetBtn = $('#j-resetBtn'),
        $gameMenu = $('#j-gameMenu'),
        $toggleBtn = $('#j-toggleBtn'),
        $gameContainer = $('#j-gameContainer'),
        isLogin = false,  //登入状态
        collectGames = [], //收藏游戏数据
        historyGames = [], //游戏历史记录数据
        collectTmpGames = [], // 缓存的收藏游戏数据
        tpl = ['<div class="game-info box {{class}}" id="{{id}}" data-subtype="{{subType}}" data-tag="{{tag}}" data-json=\'{{json}}\'>',
            '       <div class="game-pic">' +
            '         <a {{isFavorite}} data-state="0" href="javascript:;" class="game-text collect">{{collectAction}}</a>',
            '           <img class="lazy" data-original="{{categoryPic}}{{pic}}" width="200" height="200" alt="">',
            '       </div>',
            '       <div class="name">',
            '           <h4>{{name}} <sub>{{eName}}</sub></h4>',
            '       </div>',
            '       <div class="game-brief">',
            '           <div class="btn-wp text-center">',
            '                {{linkPlay}} ',
            '                {{linkDemo}}',
            '                {{jackPot}}',
            '</div>',
            '       </div>',
            '  </div>'].join('');

    /*    var DtConfig={
            gameUrl:$('#j-gameurl').val()||'',
            slotKey:$('#j-slotKey').val()||'',
            referWebsite:$('#j-referWebsite').val()||'',
            gameCode:'<%=gameCode%>'||'',
            language:'<%=language%>'||''
        };*/


    SlotMg.Reg = null;
    SlotMg.DataList = [];
    SlotMg.DataUrl = {
        PT: ['/data/slot/pc/pt.json?v=6600000012'],
        PTAISA: ['/data/slot/pc/ptaisa.json?v=6600000012'],
        MGS: ['/data/slot/pc/mg.json?v=6600000012'],
        QT: ['/data/slot/pc/qt.json?v=6600000012'],
        DT: ['/data/slot/pc/dt.json?v=6600000012'],
        AE: ['/data/slot/pc/ae.json?v=6600000012'],
        NT: ['/data/slot/pc/nt.json?v=6600000012'],
        PNG: ['/data/slot/pc/png.json?v=6600000012'],
        XIN: ['/data/slot/pc/ag.json?v=6600000012'],
        SW: ['/data/slot/pc/ptsw.json?v=6600000012'],
        CQ9: ['/data/slot/pc/cq9.json?v=6600000012'],
        PP: ['/data/slot/pc/pp.json?v=78787118'],
        PG: ['/data/slot/pc/pg.json?v=66000001012']
    };

    SlotMg.ImgUrl = {
        PT: ['/images/ptgames/'],
        PTAISA: ['/images/ptgames/'],
        PNG: ['/images/pngames/'],
        DT: ['/images/dtgames/'],
        AE: ['/images/aegames/'],
        MGS: ['/images/mggames/'],
        QT: ['/images/qtgames/'],
        NT: ['/images/ntgames/'],
        XIN: ['/images/xingames/'],
        MWG: ['/images/chessgames/'],
        SW: ['/images/ptswgames/'],
        CQ9: ['/images/cq9swgames/'],
        PP: ['/images/ppgames/'],

        PG: ['/images/pggames/']
    };

    var TTplayerhandle = $('#TTplayerhandle').val();
    var NTsession = $("#j-ntsession").val();

    SlotMg.init = function () {
        isLogin = $('#j-isLogin').val() === 'true';

        SlotMg.slotSequence()
        SlotMg.event();
        SlotMg.search();

        $(document).on('click', '.j-login', function () {
            if (!isLogin) {
                layer.open({
                    skin: 'tips-layer',
                    content: '请先登入账户！！',
                    btn: ['确定'],
                    yes: function () {
                        openLoginModule();
                    }
                });
                return false;
            } else {
                fastTransferInit(this);
            }
        });


        // 获取收藏游戏

        if (isLogin) {
            var def = SlotMg.queryCollectGames();

            def.done(function (data) {

                if (data.gameList != '' && data.gameList != null) {
                    collectGames = JSON.parse(data.gameList);
                } else {
                    collectGames = [];
                }

                favoriteInit(true);

            }).fail(function () {
                alert('获取数据失败');
            });

            function favoriteInit() {
                //收藏游戏点击事件
                $(document).on('click', '.game-info .collect', function () {

                    var $that = $(this),
                        state = $that.attr('data-state'),
                        tmpObj = $that.closest('.game-info').data('json');

                    if (state == '0') { //没有收藏
                        $that.attr('data-state', 1).html('<i class="iconfont icon-love"></i>');
                        tmpObj.isCollect = true;
                        SlotMg.saveCollectGames(tmpObj, false);

                    } else if (state == '1') {//已经收藏
                        $that.attr('data-state', 0).html('<i class="shoucang"></i>');
                        SlotMg.saveCollectGames(tmpObj, true);
                    }
                });
                $(document).on('click', '.game-info .collect[data-favorite]', function () {
                    var $that = $(this),
                        tmpObj = $that.closest('.game-info').data('json');
                    SlotMg.saveCollectGames(tmpObj, true);
                    //console.table(collectGames);
                    $that.closest('.game-info').remove();
                });

                //收藏游戏列表
                $('#j-favoriteAction').on('click', function () {

                    if (isLogin === true) {
                        window.sessionStorage && (sessionStorage.__searchFavorite__ = true);
                        console.log(collectGames);
                        SlotMg.builHtml(collectGames);
                        SlotMg.lazyload();
                        var timer = setTimeout(function () {
                            SlotMg.setCollectState();
                            timer = null;
                        }, 200);
                    }
                });
            }

        } else {
            $(document).on('click', '.game-info .collect', function () {
                layer.open({
                    skin: 'tips-layer',
                    content: '请先登入账户！！',
                    btn: ['确定'],
                    yes: function () {
                        openLoginModule();
                    }
                });
            })
        }

        $('.j-login1').on('click', function () {
            if (!isLogin) {
                openLoginModule();
            }
        });


        //游戏记录
        //=======

        /*        try{
                    if(localStorage.getItem('hisotryGames')) {
                        historyGames=JSON.parse(localStorage.getItem('hisotryGames'))
                    } else {
                        historyGames=[];
                        localStorage.setItem('hisotryGames','');
                    }
                    $(document).on('click','.game-info .btn',function(e){
                        var tmpObjStr=$(this).closest('.game-info').data('json');
                        SlotMg.saveHistory(tmpObjStr);
                    });
                }catch(err){
                    console.log('游戏记录出错');
                }
                //游戏历史记录列表
                $('#j-historyAction').on('click',function(){
                    SlotMg.builHtml(historyGames);
                    SlotMg.lazyload();
                });*/
    };

    SlotMg.event = function () {
        $filterBtn.on('click', function (e) {
            e.preventDefault();
            var $this = $(e.currentTarget),
                type = $this.data('toggle'),
                $parent = $this.closest('.search-row');

            if (type === 'game-tab') {
                SlotMg.reset();
                var href = $this.attr('href');

                !$(href).hasClass('active') && SlotMg.setActiveClass($(href));
            }

            SlotMg.setActiveClass($this);
            SlotMg.showGames();
        });

        $toggleBtn.on('click', SlotMg.toggleShow);
        $resetBtn.on('click', function () {
            SlotMg.reset;
            var tmpType = $gameMenu.find('li.active a').attr('data-tab').toLowerCase();

            $filter.find('#tab-' + tmpType + ' .search-row a').eq(2).trigger('click');
            $filter.find('#tab-' + tmpType + ' .search-row:not(:first-child) a:first-child').trigger('click');
        });

    };


    /**
     * 根据游戏的大类动态获取游戏数据
     * @param type
     * @param callback
     */
    SlotMg.getByCategory = function (type, callback) {
        var urls = SlotMg.DataUrl[type];
        if (!urls) return;

        if (urls === 'load') {
            callback();
            return;
        }
        var dfds = [];
        for (var i = 0; i < urls.length; i++) {
            dfds.push($.getJSON(urls[i], function (data) {
                SlotMg.DataList = SlotMg.DataList.concat(data);
            }));
        }
        $.when.apply(null, dfds)
            .done(function () {
                SlotMg.DataUrl[type] = 'load';
                callback();
            })
            .fail(function () {

            });
    };

    /**
     *设置过滤信息
     */
    SlotMg.setFilter = function () {
        var $btn = $filter.find('.tab-hd a.active,.tab-panel.active a.active');
        var tmpObj = {
            'category': '', //老虎机平台类型
            'type': '',  //老虎机类型 :经典,电动吃角子
            'line': '', // 老虎机线性类型
            'subType': '', // 第二种类型类型
            'tag': []
        };
        tmpObj.category = $filter;
        var tmp = {'tag': []};
        $.each($btn, function (index, obj) {
            var dataValue = $(obj).data('value');
            if (dataValue) {
                var tagvalue = dataValue['tag'];
                if (tagvalue) {
                    !tmp.tag.includes(tagvalue) && tmp.tag.push(tagvalue);
                } else {
                    tmp = $.extend(tmp, dataValue);
                }
            }
        });

        var ret = $.extend(tmpObj, tmp);

        builReg(ret.tag);

        function builReg(filterArr) {
            if (filterArr == 0) return;
            var retStr = '';
            for (var i = 0; i < filterArr.length; i++) {
                retStr += '(?=.*,' + filterArr[i] + ')';
            }
            //retStr=retStr.replace(/\|+$/, '');
            SlotMg.Reg = new RegExp('^' + retStr + '.*$');

        }

        /* console.group('filter信息');
         console.log(ret);
         console.log(SlotMg.Reg);
         console.groupEnd();*/

        return ret;
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

                } else {
                    ret.push('el.' + p + '=="' + arr[p] + '"');
                }
            }
        }
        return ret;

    };

    /**
     * 多条件查找游戏获取游戏
     */
    SlotMg.showGames = function () {

        var filter = SlotMg.setFilter();

        SlotMg.getByCategory(filter.category, function () {

            var whereArr = SlotMg.getWhere(filter);

            if (whereArr.length) {

                // 只有 SW PT热门 允许[ONLYPTSLOT]类型游戏
                if (filter.category == "SW" && filter.tag == "PTHOT") {
                    /* do something */
                } else {

                    // 其他一律不允许
                    whereArr.push("el.subType!=\"ONLYPTSLOT\"");
                }

                var _funStr = ' return ' + whereArr.join(' && ');
                var _tmpFun = new Function("el", _funStr);  // 根据动态生成查询条件,动态生成方法

                // console.group('动态function字符串');
                // console.log(_funStr);
                // console.groupEnd();

                var _d = SlotMg.DataList.filter(_tmpFun);

                SlotMg.builHtml(_d);
            } else {
                SlotMg.builHtml(SlotMg.DataList.slice(0, 200));// 最多只获取100个数据
            }

            SlotMg.setCollectState();
        });
    };

    /**
     * 游戏顶部菜单点击事件
     */
    SlotMg.menu = function () {

        $(document).on("click", "#j-gameMenu a", function (e) {



            var $this = $(e.currentTarget);

            if (!$this.attr('data-tab') || $this.attr('data-tab') == "OTHER") return;
            if (!$this.attr('data-value')) return;

            $('#j-gameContainer').show();
            $('.search_container').show();
            $('#j-hotContainer').hide();

            SlotMg.reset();

            var value = $this.attr('data-value').replace(/"/g, '\\\"');
            var obj = $this.data('value');
            var tab = $this.data("tab");

            SlotMg.setActiveClass($this.closest('li'));

            if (obj.category) {

                var a = $filter.find('a[data-value="' + value + '"]')
                    .trigger('click');
            } else if (obj.tag) {

                var $ptTab = $('#tab-pt');
                SlotMg.reset();
                SlotMg.setActiveClass($ptTab);
                var b = $ptTab.find('a[data-value="' + value + '"]')
                    .trigger('click');
            }


            if (tab == "DT") {
                $("#dt-download-content").show();
                $("#pt-download-content").hide();
            } else {
                $("#dt-download-content").hide();
                $("#pt-download-content").show();
            }
        });

    };

    SlotMg.setActiveClass = function ($ele) {
        $ele.addClass('active').siblings().removeClass('active');
    };

    /**
     * 过滤查询信息
     */
    SlotMg.reset = function () {
        var $targetMenu = $gameMenu.find('li.active a');
        var tmpType = '';
        if ($targetMenu.length > 0) {
            tmpType = $targetMenu.attr('data-tab').toLowerCase();
        }

        var _tmp = $filter.find('#tab-' + tmpType + ' .search-row a').eq(2);
        var _agTmp = $filter.find('#tab-' + tmpType + ' .search-row a').eq(0);
        if (tmpType == "xin") {
            SlotMg.setActiveClass(_agTmp);
        } else {
            SlotMg.setActiveClass(_tmp);
        }

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

    SlotMg.toggleShow = function () {
        $filter.slideToggle();
    };

    /**
     * 查找输入框
     */
    SlotMg.search = function () {
        var $searchForm = $('#j-searchForm'), // 查找表单
            $searchIpt = $searchForm.find('.j-ipt'), // 查找输入框
            $searchSelect = $searchForm.find('.j-select'), // 查找结果显示在下拉菜单
            $searchBtn = $searchForm.find('.j-btnSearch'), //查找按钮
            $selectAction = $searchSelect.find('a'),//下拉菜单的item
            searchList = [];

        function get (v, type) {
            if (!v) return;

            searchList = SlotMg.DataList.filter(function (el) {

                return (el.name.indexOf(v) != -1 && el.category == type)
                    || (el.eName.toLowerCase().indexOf(v) != -1 && el.category == type);
            });
        }

        function buildSelect() {

            if (searchList == 0) {
                $searchSelect.html('').slideUp();
                return;
            }
            var _ret = [];
            $.each(searchList, function (i, o) {
                _ret.push('<a href="javascript:;" data-id="' + o.id + '">' + o.name + '</a>');
                if (i == 9) return false;
            });
            $searchSelect.html(_ret.join('')).slideDown();
        }

        var typeTab = "";
        $searchIpt.on('keyup', function () {
            typeTab = $('#j-gameMenu li.active a').data('tab');
            searchList = [];

            var v = $searchIpt.val();
            if (v) {
                get($searchIpt.val(), typeTab);
                buildSelect();
            }

        });
        $searchForm.on('click', '.j-select a', function (e) {

            var _id = $(e.currentTarget).data('id');
            $searchIpt.val($(e.currentTarget).text());
            $searchSelect.slideUp();

            searchList = SlotMg.DataList.filter(function (el) {
                return el.category == typeTab && el.id == _id;
            });
            SlotMg.builHtml(searchList);
            SlotMg.setCollectState();
        });

        $searchBtn.on('click', function () {
            typeTab = $('#j-gameMenu li.active a').data('tab');


            $searchIpt.val();
            get($searchIpt.val(), typeTab);
            SlotMg.builHtml(searchList);
            SlotMg.setCollectState();
        });

        $searchIpt.on('focusout', function () {
            searchList = [];
            $searchSelect.slideUp();
        });
    };

    /**
     * 获取收藏游戏
     */
    SlotMg.queryCollectGames = function () {
        return $.getJSON('/asp/queryGameStatus.php');
    };

    /**
     * 保存收藏游戏
     */
    SlotMg.saveCollectGames = function (obj, isDel) {
        if (!obj) return;
        var tmpIndex = -1;
        if (collectGames) {
            $.each(collectGames, function (index, item) {
                if (item.id === obj.id) {
                    tmpIndex = index;
                    return false;
                }
            });
            if (tmpIndex !== -1 && !isDel) { //添加模式，找不到才进行添加操作
                return;
            }
        }
        if (isDel) { //删除操作
            collectGames.splice(tmpIndex, 1);
        } else {
            collectGames.unshift(obj);
            collectGames.length > 20 && collectGames.slice(0, 19);
        }

        $.post('/asp/saveOrUpdateGameStatus.php', {'gameList': JSON.stringify(collectGames)}, function (data) {
            // _showLayer(data);

        })
    };

    SlotMg.setCollectState = function () {
        if (isLogin) {
            $.each(collectGames, function (index, ele) {
                var eClass;
                if (ele.category == 'PTAISA') {
                    eClass = ele.category + ele.id
                } else {
                    eClass = ele.id
                }
                //console.log( eClass)
                $('#' + eClass).find('.collect').attr('data-state', 1).html('<i class="iconfont icon-love"></i>');
            });
        }

    };

    SlotMg.saveHistory = function (obj) {
        if (!obj) return;
        var tmp;
        if (historyGames) {
            tmp = historyGames.filter(function (item) {
                return item.id === obj.id;
            });
            if (tmp.length > 0) {
                return;
            }
        }
        historyGames.unshift(obj);
        if (historyGames.length > 20) {
            historyGames = historyGames.slice(0, 19);
        }
        window.localStorage.setItem('hisotryGames', JSON.stringify(historyGames));
    };

    /**
     * 获取试玩连接
     * @param obj
     * @returns {string}
     */
    SlotMg.getLinkDemo = function (obj) {

        if (obj.state == 'PLA') return "";
        if (obj.category == 'MWG') return "";

        var _tmp = '';
        switch (obj.category) {
            case 'PT':
                _tmp = 'https://cachedownload.goldenrose88.com/casinoclient.html?language=zh-cn&game={{id}}&mode=offline';
                break;
            case 'PTAISA':
                _tmp = 'https://cachedownload.goldenrose88.com/casinoclient.html?language=zh-cn&game={{id}}&mode=offline';
                break;
            case 'SW':
                if (obj.subType == 'ONLYPTSLOT') {
                    _tmp = 'https://cachedownload.goldenrose88.com/casinoclient.html?language=zh-cn&game={{id}}&mode=offline';
                } else {
                    _tmp = "/game/gameLoginPtSky.php?mode=fun&gameCode=" + obj.id + "&lobby=" + window.location.host + "/gamePt.php";
                }
                break;
            case 'QT':
                _tmp = '/gameQTForTp.php?gameCode={{id}}&isfun=1&type={{type}}';
                _tmp = _tmp.replace(/\{\{type\}\}/g, obj.subType === 'H5' ? '0' : '1');
                break;

            case 'NT':
                _tmp = 'https://yx678.load.xamahaha.com/disk2/netent/demo.html?game={{id}}&language=cn';

                break;
            case 'DT':
                _tmp = 'https://play.dreamtech8.com/playSlot.aspx?gameCode={{id}}&isfun=1&type=dt&language=zh_CN';
                break;
            case 'MGS':
                if (obj.subType == 'H5') {
                    _tmp = 'https://mobile22.gameassists.co.uk/MobileWebServices_40/casino/game/launch/UFAcom/{{id}}/zh-cn?loginType=VanguardSessionToken&isPracticePlay=true&casinoId=2712&isRGI=true&authToken=&lobbyurl=/gamePt.php?showtype=MGS'
                } else if (obj.subType == 'PRIZE') {
                    _tmp = '';
                } else if (obj.subType == '3RD') {
                    _tmp = 'https://redirector3.valueactive.eu/Casino/Default.aspx?serverid=1866&applicationID=7217&ModuleID=19493&ul=zh&siteID=TNG&playmode=demo&gameid={{id}}&clientid={{clientid}}&productid={{productid}}';
                } else {
                    _tmp = 'https://redirector3.valueactive.eu/Casino/Default.aspx?applicationid=1024&theme=quickfire&usertype=5&sext1=demo&sext2=demo&csid=2712&serverid=2712&variant=TNG&ul=zh&gameid={{id}}';
                }
                break;
            case 'PNG':
                _tmp = '/gamePNGFlashForTp.php?practice=1&gameCode={{id}}';
                break;

            case 'XIN':
                _tmp = '/asp/agTryLogin.php?gameType={{id}}';
                break;
            case 'CQ9':
                _tmp = 'javascript:;';
                break;
            case 'PG':
                _tmp = 'game/pgTryGame.php?gameCode={{code}}&type=PC';
                break;
            case 'PP':

                _tmp = '/tryPPGameLogin.php?gameCode={{id}}';
                break;


            case 'AE':
                _tmp = 'https://game.fafafa3388.com/launch_demo?s=c030d8apb9&game_id={{id}}';
                break;
            default:
                break;
        }

        if (obj.id != "" || obj.code != "") {

            _tmp = _tmp.replace(/\{\{id\}\}/g, obj.id)
                .replace(/\{\{code\}\}/g, obj.code)
                .replace(/\{\{type\}\}/g, obj.type)
                .replace(/\{\{clientid\}\}/g, obj.clientid)
                .replace(/\{\{productid\}\}/g, obj.productid);

        } else {
            /* do something */
            console.log(obj)
        }

        if (obj.category == 'CQ9') {
            return '';
        } else {
            return '<a class="btn-try" href="' + _tmp + '" target="_blank" class="btn btn-demo">试玩游戏</a>';
        }

    };

    /**
     * 获取进入游戏连接
     * @param obj
     * @returns {string}
     */
    SlotMg.getLinkPlay = function (obj) {

        var _tmp = "";

        if (obj.category == 'DT' && obj.type == 'DEM') {
            return "";  //判断状态是否为试玩
        }

        switch (obj.category) {
            case 'PT':
                _tmp = '/gamePTSlotLogin.php?gameCode={{id}}&language=zh-cn';
                break;
            case 'PTAISA':
                _tmp = '/loginPtAsiaGame.php?gameCode={{id}}';
                break;
            case 'SW':
                if (obj.subType == 'ONLYPTSLOT') {
                    _tmp = '/gamePTSlotLogin.php?gameCode={{id}}&language=zh-cn';
                } else {
                    _tmp = "/game/gameLoginPtSky.php?mode=real&gameCode=" + obj.id + "&lobby=" + window.location.host + "/gamePt.php";
                }
                break;
            case 'QT':
                _tmp = '/gameQTForTp.php?gameCode={{id}}&isfun=0&type={{type}}';
                _tmp = _tmp.replace(/\{\{type\}\}/g, obj.subType === 'H5' ? '0' : '1');
                break;
            case 'NT':
                _tmp = '/ntLogin.php?game={{id}}';
                break;
            case 'DT':
                _tmp = '/game/gameLoginDT.php?gameCode={{id}}&isfun=0&language=zh_CN&clientType=0';
                break;
            case 'MGS':
                if (obj.subType == 'H5') {
                    _tmp = '/gameMGS4H5Desktop.php?gameCode={{id}}'
                } else if (obj.subType == '3RD') {
                    _tmp = '/gameMGS43RD.php?gameCode={{id}}&clientid={{clientid}}&productid={{productid}}';
                } else {
                    _tmp = '/gameMGS.php?gameCode={{id}}';
                }
                break;
            case 'PNG':
                _tmp = '/gamePNGFlashForTp.php?practice=0&gameCode={{id}}';
                break;
            case 'XIN':
                _tmp = '/loginAgSlot.php?gameType={{id}}';
                break;
            case 'CQ9':
                _tmp = 'game/cq9Login.php?gameCode={{id}}&type=PC';
                break;
            case 'PP':
                _tmp = '/gamePPLogin.php?gameCode={{id}}';
                break;
            case 'PG':
                _tmp = 'game/pgLogin.php?gameCode={{code}}&type=PC';
                break;
            case 'AE':
                _tmp = '/game/aeLogin.php?gameCode={{id}}&type=pc';
                break;
            default:
                break;
        }

        if (obj.id != "" || obj.code != "") {

            _tmp = _tmp.replace(/\{\{id\}\}/g, obj.id)
                .replace(/\{\{code\}\}/g, obj.code)
                .replace(/\{\{type\}\}/g, obj.type)
                .replace(/\{\{clientid\}\}/g, obj.clientid)
                .replace(/\{\{productid\}\}/g, obj.productid);

        } else {
            /* do something */
            console.log(obj)
        }

        return '<button data-link="' + _tmp + '" data-category="' + obj.category + '" data-subType="' + obj.subType + '" target="' + obj.category + 'Game" class="j-login play">进入游戏</button>';
    };

    SlotMg.builHtml = function (data) {

        var _ret = [], animaClass = '';

        $.each(data, function (i, o) {

            var jackpot = "";
            if (o.category == 'DT' && o.jackpot == 1) {
                jackpot = "<i class='jackpot-flag'></i>";
            } else {
                jackpot = "";
            }

            if (o.category == 'PTAISA') {
                var favid = o.category + o.id;
            } else {
                var favid = o.id;
            }

            _ret.push(tpl.replace(/\{\{name\}\}/g, o.name)
                .replace(/\{\{pic\}\}/g, o.pic)
                .replace(/\{\{id\}\}/g, o.id)
                .replace(/\{\{class\}\}/g, animaClass)
                .replace(/\{\{categoryPic\}\}/g, SlotMg.ImgUrl[o.category])
                .replace(/\{\{key\}\}/g, '')
                .replace(/\{\{eName\}\}/g, o.eName || '')
                .replace(/\{\{tag\}\}/g, o.tag.join(','))
                .replace(/\{\{json\}\}/g, JSON.stringify(o))
                .replace(/\{\{subType\}\}/g, o.subType)
                .replace(/\{\{isFavorite\}\}/g, o.isCollect ? ' data-favorite ' : '')
                .replace(/\{\{collectAction\}\}/g, o.isCollect ? '<i class="iconfont icon-love"></i> ' : '<i class="shoucang"></i>')
                .replace(/\{\{linkDemo\}\}/g, SlotMg.getLinkDemo(o))
                .replace(/\{\{linkPlay\}\}/g, SlotMg.getLinkPlay(o))
                .replace(/\{\{jackPot\}\}/g, jackpot));

        });

        $gameContainer.html(_ret);
        $('#items').html(_ret);
        SlotMg.lazyload();

    };

    SlotMg.lazyload = function () {
        $('img.lazy').lazyload();
    };

    // 上一次的游戏
    SlotMg.slotSequence = function () {

        $.post('/asp/selectLastPlayGame.php', {type: 'slot'}, function (data) {

            if (data) {

                if (data.indexOf("newpt") > -1 && data.indexOf("ptslot") > -1) {
                    // 删除ptslot，避免出现多馀游戏图示
                    data = $.grep(data, function (value) {
                        return value != "ptslot";
                    });
                }

                var _ret = [];
                var tpl = [
                    '<li class="slot-item j-slot-{{slotType}}">',
                    '<a data-tab="{{slotType}}" data-value={"category":"{{category1}}"} href="javascript:;">',
                    '<div class="slot-icon">',
                    '<img src="images/slotGame/{{slotTypeImg}}.png?v=1" alt="">',
                    '</div>',
                    '<h3>{{slotTypeName}}</h3>',
                    '</a>',
                    '</li>'
                ].join('');

                $.each(data, function (i, o) {

                    if (o == "ptslot") {
                        o = "newpt";
                    }

                    var platFrom = '';
                    var tab = '';
                    var category = '';
                    switch (o) {
                        case 'ptasia':
                            platFrom = 'PT国际';
                            tab = 'PTAISA';
                            category = 'PTAISA';
                            break;
                        case 'pg':
                            platFrom = 'PG老虎机';
                            tab = 'PG';
                            category = 'PG';
                            break;
                        case 'slot':
                            platFrom = 'DT老虎机';
                            tab = 'DT';
                            category = 'DT';
                            break;
                        case 'ameba':
                            platFrom = 'AE老虎机';
                            tab = 'AE';
                            category = 'AE';
                            break;
                        case 'newpt':
                            platFrom = 'PT老虎机';
                            tab = 'PT';
                            category = 'PT';
                            break;
                        case 'cq9':
                            platFrom = 'CQ9老虎机';
                            tab = 'CQ9';
                            category = 'CQ9';
                            break;
                        case 'sw':
                            platFrom = 'SW老虎机';
                            tab = 'SW';
                            category = 'SW';
                            break;
                        case 'pp':
                            platFrom = 'PP老虎机';
                            tab = 'PP';
                            category = 'PP';
                            break;
                        case 'mg':
                            platFrom = 'MG老虎机';
                            tab = 'MGS';
                            category = 'MGS';
                            break;
                        case 'png':
                            platFrom = 'PNG老虎机';
                            tab = 'PNG';
                            category = 'PNG';
                            break;
                        case 'qt':
                            platFrom = 'QT老虎机';
                            tab = 'QT';
                            category = 'QT';
                            break;
                        case 'nt':
                            platFrom = 'NT老虎机';
                            tab = 'NT';
                            category = 'NT';
                            break;
                        case 'agin':
                            platFrom = 'AG老虎机';
                            tab = 'XIN';
                            category = 'XIN';
                            break;
                        default:
                            break;
                    }



                    _ret.push(
                        tpl.replace(/\{\{slotType\}\}/g, tab)
                            .replace(/\{\{slotTypeImg\}\}/g, o)
                            .replace(/\{\{slotTypeName\}\}/g, platFrom)
                            .replace(/\{\{category1\}\}/g, category + '')
                    );
                });


                $('#j-gameMenu').append(_ret);
                SlotMg.menu();
                SlotMg.setGameTrigger();
            }

        });
    };

    // 默认游戏
    SlotMg.setGameTrigger = function () {



        var type = getQueryString('showtype');
        var $gameMenu = $('#j-gameMenu');

        var index = $gameMenu.find(".j-slot-" + type).index();

        if(index > 9){

            setTimeout(function () {
                $('.js-other-item').trigger('click');
            },1000);


        }


        _trigger(type);


        function _trigger(type) {



            if (!type || typeof type == "undefined") {

                $gameMenu.find('#hot-game').addClass('active').trigger('click');
            } else {

                if (type == "PP" ||type == "DT" || type == "PTAISA" || type == "PG" ||
                    type == "AE"  || type == "PT" ||
                    type == "CQ9" || type == "SW" || type == "MGS" ||
                    type == "PNG" || type == "QT" || type == "NT" || type == "XIN") {




                    $gameMenu.find(".j-slot-" + type + " a").trigger('click');

                } else {
                    $gameMenu.find('.j-slot-PT a').trigger('click');
                }

            }
        }
    };

    w.SlotMg = SlotMg;
})(window);


$(function () {

    SlotMg.init();

    $('.logo-cont').attr('href', '/slotGame.jsp');

    $('.js-slot-navbar-list').addClass('active').siblings('.active').removeClass('active');

    // $('.navbar-list').hover(function () {
    //
    //     $(this).find('.game-dropdown').show().siblings().find('.game-dropdown').hide();
    // });





//热门游戏
    $('#hot-game').on('click', function () {
        $('#j-gameContainer').hide();
        $('.search_container').hide();
        $('#j-hotContainer').show();
    });


//其它游戏显示及隐藏
    $('.js-other-item').on('click', function () {

        if ($(this).hasClass('active')) {

            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }

        if ($('#j-gameMenu').hasClass('active')) {

            $('#j-gameMenu').removeClass('active');
        } else {
            $('#j-gameMenu').addClass('active');
        }
    });

//


//左侧快速转账收起
    var _DEFAULT_INIT = false;
    $(function () {

        if (_DEFAULT_INIT == false) {
            setDtJackPot();

            loadJS('https://tickers.playtech.com/jackpots/new_jackpotjs.js', newJackpotJsAfter, document.body);

            _DEFAULT_INIT = true;
        }
    });

//游戏转账
    $('#j-transferAction').off().on('click', function () {
        transferMonery();
    });
});

function newJackpotJsAfter() {
    loadJS('/js/jackpot-ticker.js', "", document.body);
}

$(window).load(function () {

    // 默认游戏
    setTimeout(function () {
        // 中奬名单
        setWinnerList();
    }, 200);

    // 进入DT游戏 webgl
    setDtwebgl();

    // 优惠活动跳转
    checkUrl();

});


function fastTransferInit(e) {

    var credit = $('#j-credit').val();
    var link = $(e).data('link');
    var category = $(e).data('category');
    var platFrom = '';
    var subType = $(e).data('subtype');

    switch (category) {
        case 'PT':
            platFrom = 'ptslot';
            break;
        case 'PTAISA':
            platFrom = 'ptasia';
            break;
        case 'SW':
            platFrom = 'slot';
            break;
        case 'QT':
            platFrom = 'slot';
            break;
        case 'NT':
            platFrom = 'slot';
            break;
        case 'DT':
            platFrom = 'slot';
            break;
        case 'MGS':
            platFrom = 'slot';
            break;
        case 'PNG':
            platFrom = 'slot';
            break;
        case 'XIN':
            platFrom = 'agin';
            break;
        case 'CQ9':
            platFrom = 'cq9';
            break;
        case 'PG':
            platFrom = 'pg';
            break;
        case 'PP':
            platFrom = 'pp';
            break;
        case 'AE':
            platFrom = 'ameba';
            break;
        default:
            break;
    }


    // PT游戏 使用PT转帐
    if (subType == "ONLYPTSLOT") {
        category = "PT";
        platFrom = "ptslot";
    }


        $.post('/asp/getGameMoney.php', {gameCode: platFrom}, function (data) {


            if (data) {
                var money = data.split('元')[0];


                $('.js-des-account').html(category);
                $('.js-des-money').html(data);

                if (parseFloat(money) < 5) {
                    $('.js-transfer-title').html('您的' + category + '账户已<span class="money">不足5元</span> ，是否快速转帐？');

                    if (parseFloat(credit) < 1) {

                        layer.open({
                            skin: 'tips-layer less-money-layer',
                            title: false,
                            content: '<img src="/images/tips.png" alt="" id="tip"><div id="tip-cont"><h3><i class="iconfont icon-taoxin"></i>温馨提示：</h3>您的主账户已<span class="money">不足1元</span>，<br>是否点击下方按钮去充值？</div>',
                            area: ['525px', ''],
                            btn: ['快速充值', '进入游戏'],
                            yes: function () {
                                window.location.href = "/userManage.php?action=1"
                            },
                            btn2: function () {
                                window.location.href = link;
                            }
                        });
                    } else {
                        layer.open({
                            type: 1,
                            skin: 'transfer-layer tips-layer',
                            title: '快速转账',
                            content: $('#fast-deposit-cont'),
                            area: ['500px', ''],
                            btn: ['快速转账', '进入游戏'],
                            success: function (layero, index) {

                                var num = '';
                                switch (platFrom) {
                                    case 'slot':
                                        num = '6009';
                                        break;
                                    case 'ptasia':
                                        num = '6011';
                                        break;
                                    case 'ameba':
                                        num = '6014';
                                        break;
                                    case 'cq9':
                                        num = '6012';
                                        break;
                                    case 'pg':
                                        num = '6013';
                                        break;
                                    case 'pp':
                                        num = '6019';
                                        break;
                                }


                                $.ajax({
                                    type: "post",
                                    url: "/asp/getYouHuiConfig.php",
                                    data: {},
                                    success: function (data) {
                                        //console.log(data);

                                        var newHtml = '';
                                        for (var i = 0; i < data.length; i++) {
                                            if (data[i].platformId == num) {

                                                newHtml += '<option value="' + data[i].id + '" ' +
                                                    'data-betmultiples="' + data[i].betMultiples + '"  ' +
                                                    'data-percent="' + data[i].percent + '"  ' +
                                                    'data-titleid="' + data[i].titleId + '" ' +
                                                    'data-platformid="' + data[i].platformId + '"  ' +
                                                    'data-limitmoney="' + data[i].limitMoney + '">' +
                                                    data[i].aliasTitle + '</option>';
                                            }

                                        }
                                        if (newHtml != '') {

                                            $('.js-save-cont').show();

                                            $('#coupon').html('<option value="" selected>不使用优惠</option>' + newHtml);
                                        } else {
                                            $('.js-save-cont').hide();
                                        }

                                    }
                                });

                            },
                            yes: function () {


                                var couponOptionVal = $('#coupon option:selected').val();


                                if (couponOptionVal && couponOptionVal != '') {

                                    checkSelfYouHuiSubmit(link);
                                } else {
                                    fastTranferMethod(platFrom, link);
                                }

                            },
                            btn2: function () {
                                window.location.href = link;
                            }
                        });
                    }

                } else {
                    window.location.href = link;
                }
            }
        });



    //优惠类型切换
    $('#coupon').change(function () {
        getSelfYouhuiAmount();
    });

    //输入转账金额之后
    $('#transfer-money').blur(function () {

        if ($(this).val() != '') {
            getSelfYouhuiAmount();
        }
    });

    //正常转账
    function fastTranferMethod(platFrom, link) {
        var transferGameMoney = $('#transfer-money').val();
        $.post("/asp/updateGameMoney.php", {
            "transferGameOut": 'self',
            "transferGameIn": platFrom,
            "transferGameMoney": transferGameMoney
        }, function (returnedData, status) {
            if ("success" == status) {
                closeProgressBar();
                if (returnedData === "转账成功！") {
                    window.location.href = link;
                } else {
                    alert(returnedData);
                }
            }
        });
    }

    //含优惠转账
    function checkSelfYouHuiSubmit(link) {
        var couponOption = $('#coupon option:selected');

        var transferGameMoney = $('#transfer-money').val();
        var couponOptionVal = couponOption.val();
        var platformId = couponOption.data('platformid');
        var titleId = couponOption.data('titleid');


        if (transferGameMoney == null || transferGameMoney == '') {
            alert("请输入转账金额！");
            return;
        }

        if (isNaN(transferGameMoney)) {
            alert("转账金额只能为数字！");
            return;
        }

        var rex = /^[1-9][0-9]+$/;

        if (!rex.test(transferGameMoney)) {
            _showLayer("抱歉，存送金额只能是大于或等于10元的整数哦。", '');
            return;
        }


        $.post('/asp/getSelfYouHuiObject.php', {
            "id": couponOptionVal,
            "platformId": platformId,
            "titleId": titleId,
            "remit": transferGameMoney
        }, function (respData) {

            if (respData.indexOf('成功') != -1) {
                window.location.href = link;
            } else {
                alert(respData);
            }
        });
    }

    //红利计算
    function getSelfYouhuiAmount() {

        var couponOption = $('#coupon option:selected');

        var limitMoney = 0;
        var betMultiples = 0;
        var percent = 0;

        if (couponOption.val() != '') {
            limitMoney = couponOption.data('limitmoney');
            betMultiples = couponOption.data('betmultiples');
            percent = couponOption.data('percent');
        }


        var value = $('#transfer-money').val();

        var money = 0;

        if (!(null == value || '' == value || isNaN(value))) {

            money = value * (percent) > (limitMoney) ? (limitMoney) : value * (percent);
        } else {
            money = 0;
        }

        $("#giftMoney").html(money.toFixed(2));
        $('#waterTimes').html(betMultiples);
    }
}


function getQueryString(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return false;
    if (!results[2]) return false;
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

// 进入DT游戏 webgl
function setDtwebgl() {
    $(document).on('click', '.game-info[data-subtype="DT-1"] .btn', function () {
        if (isIE() || !checkwebgl()) { //ie 浏览器及不支持webgl的
            $('#j-tip').modal('show');
            return false;
        }
        return true;
    });

    // 判断是否支持webgl
    function checkwebgl() {
        var cvs = document.createElement('canvas');
        var contextNames = ['webgl', 'experimental-webgl', 'moz-webgl', 'webkit-3d'];
        var ctx;
        if (navigator.userAgent.indexOf('MSIE') >= 0) {
            try {
                ctx = WebGLHelper.CreateGLContext(cvs, 'canvas');
            } catch (e) {
            }
        } else {
            for (var i = 0; i < contextNames.length; i++) {
                try {
                    ctx = cvs.getContext(contextNames[i]);
                    if (ctx) {
                        break;
                    }
                } catch (e) {

                }
            }
        }
        if (ctx) {
            return true;
        }
        return false;

    }

    //ie?  判断是否是ie浏览器
    function isIE() {
        if (!!window.ActiveXObject || "ActiveXObject" in window)
            return true;
        else
            return false;
    }
}

// 中奬名单
function setWinnerList() {

    var tpl = '<li class="clear">'
        + '<a href={{link}} target="_blank" class="block">'
        + '<div class="gamepic"><img src={{img}}></div>'
        + '<div class="wintext"><div>'
        + '<p>会员{{winner}}</p>'
        + '<p>投注<span class="red">{{bet}}元</span>喜提<span class="red">¥{{win}}元</span></p>'
        + '<p class="name">{{gamename}}({{type}})</p>'
        + '</div><div></div></div>' + '</a>'
        + '</li>';

    $.post("/data/winnerNew.json?v=3", function (jsonData) {
        var html = buildHtml(jsonData);
        $(".infoList").html(html);
        $(".left-loop").slide({
            mainCell: ".infoList",
            effect: "leftMarquee",
            vis: 5,
            interTime: 40,
            autoPlay: true
        });

    });

    function buildHtml(data) {
        var winnerList = eval(data);
        if (winnerList.length > 0) {
            var html = "";
            for (var i = 0; i < winnerList.length; i++) {
                var obj = winnerList[i];
                html += tpl.replace(/\{\{winner\}\}/g, obj.winner).replace(
                    /\{\{bet\}\}/g, obj.bet).replace(/\{\{win\}\}/g,
                    obj.win).replace(/\{\{link\}\}/g, obj.link).replace(
                    /\{\{img\}\}/g, obj.img).replace(/\{\{gamename\}\}/g,
                    obj.gamename).replace(/\{\{type\}\}/g, obj.type)
            }
            return html;
        }
    }
}

// DT奬池
function setDtJackPot() {
    $.post("/asp/dtJackpot.php", function (response) {
        if (response.code != "00000") {
            console.log("DT奖池系统繁忙，请刷新后再次进行查看！");
        } else {
            var pot = response.pot;
            var dot = pot.split(".");

            if (CountUp) {
                var demo = new CountUp("j-dtCount", pot, 9457295, 2, 800000000, {
                    useEasing: true,
                    useGrouping: true,
                    separator: ',',
                    decimal: '.',
                    prefix: '',
                    formate: true,
                    suffix: ''
                });
                demo.start();
            }
        }
    });
}

document.onkeydown = keyDownSearch;

function keyDownSearch(e) {

    var theEvent = e || window.event;
    var code = theEvent.keyCode || theEvent.which || theEvent.charCode;
    if (code == 13) {
        $('.open-login-layer .layui-layer-btn0').click();
        return false;
    }
    return true;
}

//判断进入最新优惠时对应老虎机优惠
function checkUrl() {
    var type = (window.location.pathname == "/slotGame.jsp") ? 1 : 0;
    $('.js-promotion-navbar-list').find('a').attr('href', '/promotion.jsp?type=1');
}


//游戏转账
function transferMonery() {
    var transferGameOut = $("#transferGameOut").val();
    var transferGameIn = $("#transferGameIn").val();
    var transferGameMoney = $("#transferGameMoney").val();

    if (transferGameMoney != '') {
        openProgressBar();

        $('#j-transferAction').attr('disabled', 'disabled');

        $.post("/asp/updateGameMoney.php", {
            "transferGameOut": transferGameOut,
            "transferGameIn": transferGameIn,
            "transferGameMoney": transferGameMoney
        }, function (returnedData, status) {

            $('#j-transferAction').removeAttr('disabled');

            if ("success" == status) {
                closeProgressBar();
                alert(returnedData);
                window.location.reload();
            }
        });
    } else {
        _showLayer('请输入金额！', '确定')
    }
}

function loadJS(url, implementationCode, location) {
    //url is URL of external file, implementationCode is the code
    //to be called from the file, location is the location to
    //insert the <script> element

    var scriptTag = document.createElement('script');
    scriptTag.src = url;

    scriptTag.onload = implementationCode;
    scriptTag.onreadystatechange = implementationCode;

    location.appendChild(scriptTag);
}


/** 未用到 **/
//奖池动态效果
function setJackPot() {
    if (CountUp) {
        var demo = new CountUp("j-jackpotCount", 411264389, 919457295, 2,
            30000000000, {
                useEasing: true,
                useGrouping: true,
                separator: ',',
                decimal: '.',
                prefix: '',
                formate: true,
                suffix: ''
            });
        demo.start();
    }
}

/**
 * html 字符转义
 * @param str
 * @returns {string}
 * @private
 */
function htmlEncode(str) {
    var s = "";
    if (str.length == 0) return "";
    s = str.replace(/&/g, "&amp;");
    s = s.replace(/</g, "&lt;");
    s = s.replace(/>/g, "&gt;");
    s = s.replace(/ /g, "&nbsp;");
    s = s.replace(/\'/g, "&#39;");
    s = s.replace(/\"/g, "&quot;");
    s = s.replace(/\n/g, "<br>");
    return s;
}

/**
 * html 字符反转义
 * @param str
 * @returns {string}
 * @private
 */
function htmlDecode(str) {
    var s = "";
    if (str.length == 0) return "";
    s = str.replace(/&amp;/g, "&");
    s = s.replace(/&lt;/g, "<");
    s = s.replace(/&gt;/g, ">");
    s = s.replace(/&nbsp;/g, " ");
    s = s.replace(/&#39;/g, "\'");
    s = s.replace(/&quot;/g, "\"");
    s = s.replace(/<br>/g, "\n");
    return s;
}