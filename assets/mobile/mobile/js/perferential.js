/**
 * Created by 1040170 on 2018/5/9.
 */

header.find('.header-title').text('最新优惠');
footer.find('.item.vip').addClass('active').find('i').addClass('active');
footer.find('.item.preferential').addClass('active').find('i').addClass('active');

$(function () {
    new Promotion();
});

(function () {

    var tpl = [
        '<div class="page show promo-item" data-type="{{type}}">',
        '	<div class="promo-box" data-url="{{url}}" data-redirecturl="{{redirectUrl}}">',
        '		<img src="{{image}}" class="p-img" style="width:50%;"  />',
        '		<div class="title">',
        '			<div class="promo-tit">{{title}}</div>',
        '			<div class="promo-desc">{{subTitle}}</div>',
        '			<button>',
        '   			<p class="fl">显示详情</p>',
        '			</button>',
        '		</div>',
        '	</div>',
        '	<div class="promo-tit-box"></div>',
        '  <div class="promotion-content" style="display: none;" ></div>',
        '</div>'].join('');

    var config = {
        'htmlurl': '/data/promotion',
        'version': '5000000029',
        'dataUrl': '/data/promotion/promotion.json?v=5000000029',
        'menu': '#j-menu',
        'listWrapper': '.page-boxs',
        'infoClass': '.promo-box',
        'contentClass': '.promotion-content',
        'itemClass': '.promo-item'
    };

    function Promotion() {
        if (!this instanceof Promotion) {
            return new Promotion();
        }
        this.$menu = $(config.menu);
        this.$menuItem = this.$menu.find('div');
        this.$promotions = null;
        this.$listWrapper = $(config.listWrapper);
        this.tplHtml = tpl;
        this.init();
    }

    /**
     * 优惠活动侧边菜单点击事件
     */
    Promotion.prototype.navToggle = function () {
        var _this = this;
        this.$menuItem.on('click', function () {
            var type = $(this).data('type');
            var parent = $(this);

            parent.addClass('active').siblings().removeClass('active');
            _this.$promotions.hide();

            // if (type == 'ALL') {
            //     $.each(_this.$promotions, function (index, ele) {
            //         var $this = $(ele);
            //         $this.fadeIn();
            //     });
            // } else {
                $.each(_this.$promotions, function (index, ele) {
                    var $this = $(ele);
                    $this.data('type').indexOf('|' + type) != -1 ? $this.fadeIn() : $this.hide();
                });
            // }

            return false;
        });
    }

    /**
     * 活动详情的显示或者隐藏
     */
    Promotion.prototype.infoToggle = function () {
        $(document).on('click', config.infoClass, function (e) {
            e.preventDefault();

            var _$current = $(e.currentTarget);
            var url = $(this).attr('data-url');
            var redirectUrl = $(this).attr('data-redirectUrl');

            if (redirectUrl != "" && redirectUrl != "undefined") {
                window.open(redirectUrl, "_blank");
            } else {

                var show = function (data) {
                    _$current.find(config.contentClass).html(data).show();
                    _$current.siblings(config.contentClass).html(data).slideToggle();
                    _$current.toggleClass('active');
                };

                $.get(url, show).fail(function () {
                    throw '无法获取的活动详情:' + url;
                });

                return false;
            }
        });


    }
    /**
     * 隐藏活动详情按钮事件
     */
    Promotion.prototype.infoHide = function () {
        $(document).on('click', config.contentClass + ' .btn-reback', function (e) {
            var _$current = $(e.currentTarget);
            var top = _$current.parents('.history-item').offset().top;
            _$current.parents(config.contentClass).slideUp();
            $("html, body").animate({
                scrollTop: top
            }, 350);
        });

    }

    Promotion.prototype.init = function () {
        var _this = this;
        var callback = function (data) {
            if (data.success) {
                var _data = data.data, html = [], d = new Date();

                for (var i = 0; i < _data.length; i++) {
                    var obj = _data[i];
                    var tplHtml = _this.tplHtml;

                    tplHtml = tplHtml
                        .replace(/\{\{index\}\}/g, i)
                        .replace(/\{\{title\}\}/g, obj.title)
                        .replace(/\{\{subTitle\}\}/g, obj.subTitle)
                        .replace(/\{\{type\}\}/g, obj.type)
                        .replace(/\{\{name\}\}/g, obj.name)
                        .replace(/\{\{image\}\}/g, obj.image + '?v=' + config.version)
                        .replace(/\{\{redirectUrl\}\}/g, obj.redirectUrl)
                        .replace(/\{\{url\}\}/g, obj.url);

                    html.push(tplHtml);
                }

                _this.$promotions = _this.$listWrapper.html(html.join('')).find(config.itemClass);
                _this.navToggle();
                _this.infoToggle();
                _this.infoHide();
                _this.$menuItem.first().trigger('click');

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

