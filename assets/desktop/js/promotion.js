//    顶部导航

$(function () {
    $('.js-promotion-navbar').addClass('active').siblings().removeClass('active');
    setTimeout(checkTypePromotion, 200);
});

function checkTypePromotion() {
    var url = location.href;
    var type = url.split('=')[1];

    if (type == 2) {
        console.log(2)
        $('#j-tab-promotion li:nth-of-type(3) a').trigger('click');

    } else if (type == 1) {
        $('#j-tab-promotion li:nth-of-type(2) a').trigger('click');
    }
}

var tplLeft = [
    '<div class="slider" data-url="{{url}}" data-redirectUrl="{{redirectUrl}}">',
    '<div data-type="{{type}}" class="promotion-item">',
    '<div class="promotion-info"">',
    '<div class="pic">{{cover}}{{coverImg}}<img class="lazy" data-original="{{image}}" src="{{image}}"></div>',
    '<div class="promotion-intro">',
    '<div class="clear">',
    '<h3 class="title">{{title}}</h3>',
    '<div class="type-name">{{typeName}}</div>',
    '</div>',
    '<div class="subtitle">{{subTitle}}</div>',
    '<div class="active-time"><i class="iconfont icon-history"></i>活动时间:{{activityTime}}</div>',
    '</div>',
    '</div>',
].join('');

(function () {
    var config = {
        'pciurl': '/images/promotion',
        'htmlurl': '/data/promotion',
        'version': '5000000029',
        'dataUrl': '/data/promotion/promotion.json?v=5000000029',
        'menu': '#j-tab-promotion',
        'listWrapper': '#j-promotion-list',
        'infoClass': '.promotion-info',
        'contentClass': '.promotion-content',
        'itemClass': '.promotion-item'
    };

    function Promotion() {
        if (!this instanceof Promotion) {
            return new Promotion();
        }
        this.$menu = $(config.menu);
        this.$menuItem = this.$menu.find('a');

        this.$menuItem.on('click', function () {
            var type = $(this).data('type');
            Promotion.prototype.init(type);
        });
        this.init('ALL');

        $("img.lazy").lazyload({
            event: "scrollstop",
            effect: "fadeIn",
            load: function (i, e) {
                $(".promotion-info .pic img").css({"width": "100%", "margin-top": "0px", "margin-left": "0px"});
            }
        });
    }

    Promotion.prototype.init = function (type) {

        var _DATA_LIST = {
            "": [],
            "ALL": [],
            "PROJECT": [],
            "MONTH": [],
            "LIMITED": [],
            "MOBILE": [],
            "FORUM": []
        };

        var callback = function (data) {
            if (data.success) {
                var _alldata = data.data,
                    arr = [];

                //资料整理SHOW
                for (var i = 0; i < _alldata.length; i++) {

                    var objDate = _alldata[i];
                    var typeobj = objDate.type.split("|");

                    for (var j = 0; j < typeobj.length; j++) {
                        var title = typeobj[j];
                        _DATA_LIST[title].push(objDate);
                    }
                }

                //整理TYPE
                var showData = _DATA_LIST[type];


                for (var k in showData) {

                    var obj = showData[k];
                    var tplLeftHtml = tplLeft;

                    tplLeftHtml = tplLeftHtml
                        .replace(/\{\{title\}\}/g, obj.title)
                        .replace(/\{\{url\}\}/g, obj.url)
                        .replace(/\{\{type\}\}/g, obj.type)
                        .replace(/\{\{typeName\}\}/g, obj.typeName)
                        .replace(/\{\{subTitle\}\}/g, obj.subTitle)
                        .replace(/\{\{image\}\}/g, obj.image + '?v=' + config.version)
                        .replace(/\{\{redirect\}\}/g, obj.redirect)
                        .replace(/\{\{activityTime\}\}/g, obj.activityTime)

                    if (obj.redirect == true) {
                        tplLeftHtml = tplLeftHtml.replace(/\{\{redirectUrl\}\}/g, obj.redirectUrl + '?v=' + config.version);
                    } else {
                        tplLeftHtml = tplLeftHtml.replace(/\{\{redirectUrl\}\}/g, 'javascript:;');
                        tplLeftHtml = tplLeftHtml.replace(/\{\{url\}\}/g, obj.url + '?v=' + config.version);
                    }

                    var today = new Date();
                     var dd = today.getDate();
                    var mm = today.getMonth() + 1;
                    var yyyy = today.getFullYear();

                    var endDate = obj.endDate.split("-");

                    endDate[1] = parseInt(endDate[1]);
                    endDate[2] = parseInt(endDate[2]);

                    var nowTime=new Date(yyyy,mm,dd);
                    var endTime=new Date(endDate[0],endDate[1],endDate[2]);

                    if(nowTime.getTime() > endTime.getTime()){
                        tplLeftHtml = tplLeftHtml.replace(/\{\{cover\}\}/g, "<div class='cover'></div>");
                        tplLeftHtml = tplLeftHtml.replace(/\{\{coverImg\}\}/g, "<div class='coverIcon'><img src='/images/icon/finished.png'></div>");
                    }else{
                        tplLeftHtml = tplLeftHtml.replace(/\{\{cover\}\}/g, "");
                        tplLeftHtml = tplLeftHtml.replace(/\{\{coverImg\}\}/g, "");
                    }



                    arr.push(tplLeftHtml)

                }

                $("#j-promotion-list").html(arr);

            }
        };
        //获取活动列表
        $.getJSON(config.dataUrl + '?v=' + config.version, callback).fail(function () {
            throw '无法获取到活动列表' + config.dataUrl;
        });
    };
    root = typeof exports !== "undefined" && exports !== null ? exports : window;
    root.Promotion = Promotion;
}).call(this);

$(function () {
    new Promotion();


    $(document).on('click', '.slider', function () {

        var $this = $(this),
            url = $this.data('url'),
            redirectUrl = $this.data('redirecturl'),
            $row = $this.closest('#modal-promotion'),
            $rowNext = $row.next();

        /*if(!url) return;*/

        if (url === 'undefined') {
            window.location.href = redirectUrl;
        } else {
            $.get(url, function (data) {
                $('#modal-promotion').modal('show');
                $('#modal-promotion dl').empty().append(data).insertAfter($row);
            });
        }

    });
});