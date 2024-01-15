/**
 * Created by 1040170 on 2018/12/28.
 */
//轮播图
$(function () {

    setBannerList();

    $('#arrow_prev').click(function () {
        var $item = $('#items-menu li');
        var ind = $('#items .on').index();
        var size = $('#items .item').size() - 1;
        if (ind === 0) {
            $item.eq(size).trigger("click");
        } else {
            $item.eq(ind - 1).trigger("click");
        }
    });

    $('#arrow_next').click(function () {
        var $item = $('#items-menu li');
        var ind = $('#items .on').index();
        var size = $('#items .item').size() - 1;
        if (ind === size) {
            $item.eq(0).trigger("click");
        } else {
            $item.eq(ind + 1).trigger("click");
        }
    });
});

function setBannerList() {

    var pathName = location.pathname;
    var type = '';

    switch (pathName) {
        case"/live.jsp":
            type = '真人轮播图配置';
            break;
        case"/chess.jsp":
            type = '棋牌轮播图配置';
            break;
        // case"/lotteryGame.jsp":
        //     type = '彩票轮播图配置';
        //     break;
        case"/sportGame.jsp":
            type = '体育轮播图配置';
            break;
        case"/fish.jsp":
            type = '捕鱼轮播图配置';
            break;
        case"/slotGame.jsp":
            type = '老虎机轮播图配置';
            break;
        default:
            type = '首頁轮播图配置';
            break;
    }

    var larget_html = "";
    var nav_html = "";

    var large_tpl = '<div data-title="{{title}}">' + '<a href="{{url}}" target="_blank" class="img-cont">'
        + '<img   src="{{webimg}}" />' + '</a>' + '</div>';

    //var nav_tpl = '<li><a href="{{url}}" target="_blank">{{title}}</a></li>';


    ///data/banner.json?v=12

    $.post("/asp/getBanner.php?v=12", {type: type}, function (jsonData) {

        if (IsJsonString(jsonData)) {
            jsonData = JSON.parse(jsonData);
        }

        if (jsonData != "") {
            var newData = jsonData.data;
            for (var i = 0; i < newData.length; i++) {
                var obj = newData[i];
                larget_html += large_tpl.replace(/\{\{title\}\}/g, obj.title)
                    .replace(/\{\{url\}\}/g, obj.url).replace(/\{\{webimg\}\}/g, obj.webimg)
                    // nav_html += nav_tpl.replace(/\{\{title\}\}/g, obj.title)
                    .replace(/\{\{url\}\}/g, obj.url)

            }
            $(".slideshow").html(larget_html);
            $('.loading').hide();
        }

        $('.slideshow').slick({
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: false,
            dots: true,
            appendDots: $('.slideshow_nav'),
            dotsClass: 'slick-dots clear',
            customPaging: function (slider, i) {
                // var title = $(slider.$slides[i]).data('title');
                return '<a></a>';
            }
        });

        $(".slideshow").hover(function () {
            $(".slideshow_left, .slideshow_right").css("opacity", ".4");
        }, function () {
            $(".slideshow_left, .slideshow_right").css("opacity", "0");
        });

        $(".slideshow_left, .slideshow_right").hover(function () {
            $(".slideshow_left, .slideshow_right").css("opacity", ".7");
        }, function () {
            $(".slideshow_left, .slideshow_right").css("opacity", "0");
        });

        $('.slideshow_left').click(function () {

            var $active = $(".slick-dots").find("li.slick-active");
            var $li = $(".slick-dots").find("li");
            var ind = $active.index();
            var length = $li.length;

            ind--;

            if (ind == -1) {
                ind = length - 1;
            }

            $li.eq(ind).trigger("click");

        });

        $('.slideshow_right').click(function () {

            var $active = $(".slick-dots").find("li.slick-active");
            var $li = $(".slick-dots").find("li");
            var ind = $active.index();
            var length = $li.length;

            ind++;

            if (ind == length) {
                ind = 0;
            }

            $li.eq(ind).trigger("click");
        });


        window.onresize = function () {
            var $height = $(".slideshow").height();
            // console.log($height)
            if($height > 450){$height=$height-100;}
            $(".slideshow_left, .slideshow_right").css("top", $height / 10 + "%");
        };


        $("img").lazyload({
            effect: "fadeIn"
        });

    });

}