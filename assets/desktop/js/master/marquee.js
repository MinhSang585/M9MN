//-----------------------------------------------------------è·‘é¦¬ç‡ˆ
//-----------------------------------------------------------è·‘é¦¬ç‡ˆ
//-----------------------------------------------------------è·‘é¦¬ç‡ˆ
$(function () {
    // å…ˆå–å¾— div#abgne_marquee ul
    // æŽ¥è‘—æŠŠ ul ä¸­çš„ li é …ç›®å†é‡è¦†åŠ å…¥ ul ä¸­(ç­‰æ–¼æœ‰å…©çµ„å…§å®¹)
    // å†ä¾†å–å¾— div#abgne_marquee çš„é«˜ä¾†æ±ºå®šæ¯æ¬¡è·‘é¦¬ç‡ˆç§»å‹•çš„è·é›¢
    // è¨­å®šè·‘é¦¬ç‡ˆç§»å‹•çš„é€Ÿåº¦åŠè¼ªæ’­çš„é€Ÿåº¦
    var $marqueeUl = $('div#abgne_marquee ul'),
        $marqueeli = $marqueeUl.append($marqueeUl.html()).children(),
        _height = $('div#abgne_marquee').height() * -1,
        scrollSpeed = 600,
        timer,
        // speed = 3000 + scrollSpeed,
        speed = 8000 + scrollSpeed,
        direction = 0,	// 0 è¡¨ç¤ºå¾€ä¸Š, 1 è¡¨ç¤ºå¾€ä¸‹
        _lock = false;

    // å…ˆæŠŠ $marqueeli ç§»å‹•åˆ°ç¬¬äºŒçµ„
    $marqueeUl.css('top', $marqueeli.length / 2 * _height);

    // å¹«å·¦é‚Š $marqueeli åŠ ä¸Š hover äº‹ä»¶
    // ç•¶æ»‘é¼ ç§»å…¥æ™‚åœæ­¢è¨ˆæ™‚å™¨ï¼›åä¹‹å‰‡å•Ÿå‹•
    $marqueeli.hover(function () {
        clearTimeout(timer);
    }, function () {
        timer = setTimeout(showad, speed);
    });

    // åˆ¤æ–·è¦å¾€ä¸Šé‚„æ˜¯å¾€ä¸‹
    $('div#abgne_marquee .marquee_btn').click(function () {
        if (_lock) return;
        clearTimeout(timer);
        direction = $(this).attr('id') == 'marquee_next_btn' ? 0 : 1;
        showad();
    });

    // æŽ§åˆ¶è·‘é¦¬ç‡ˆä¸Šä¸‹ç§»å‹•çš„è™•ç†å‡½å¼
    function showad() {
        _lock = !_lock;
        var _now = $marqueeUl.position().top / _height;
        _now = (direction ? _now - 1 + $marqueeli.length : _now + 1) % $marqueeli.length;

        // $marqueeUl ç§»å‹•
        $marqueeUl.animate({
            top: _now * _height
        }, scrollSpeed, function () {
            // å¦‚æžœå·²ç¶“ç§»å‹•åˆ°ç¬¬äºŒçµ„æ™‚...å‰‡é¦¬ä¸ŠæŠŠ top è¨­å›žåˆ°ç¬¬ä¸€çµ„çš„æœ€å¾Œä¸€ç­†
            // è—‰æ­¤ç”¢ç”Ÿä¸é–“æ–·çš„è¼ªæ’­
            if (_now == $marqueeli.length - 1) {
                $marqueeUl.css('top', $marqueeli.length / 2 * _height - _height);
            } else if (_now == 0) {
                $marqueeUl.css('top', $marqueeli.length / 2 * _height);
            }
            _lock = !_lock;
        });

        // å†å•Ÿå‹•è¨ˆæ™‚å™¨
        timer = setTimeout(showad, speed);
    }

    // å•Ÿå‹•è¨ˆæ™‚å™¨
    timer = setTimeout(showad, speed);

    $('a').focus(function () {
        this.blur();
    });
});
//-----------------------------------------------------------è·‘é¦¬ç‡ˆ
//-----------------------------------------------------------è·‘é¦¬ç‡ˆ
//-----------------------------------------------------------è·‘é¦¬ç‡ˆ




//-----------------------------------------------------------è¿”å›žé ‚éƒ¨
//-----------------------------------------------------------è¿”å›žé ‚éƒ¨
//-----------------------------------------------------------è¿”å›žé ‚éƒ¨
(function ($) {
    var goToTopTime;
    $.fn.goToTop = function (options) {
        var opts = $.extend({}, $.fn.goToTop.def, options);
        var $window = $(window);
        $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body'); // opera fix
        //$(this).hide();
        var $this = $(this);
        clearTimeout(goToTopTime);
        goToTopTime = setTimeout(function () {
            var controlLeft;
            if ($window.width() > opts.pageHeightJg * 2 + opts.pageWidth) {
                controlLeft = ($window.width() - opts.pageWidth) / 2 + opts.pageWidth + opts.pageWidthJg;
            } else {
                controlLeft = $window.width() - opts.pageWidthJg - $this.width();
            }
            var cssfixedsupport = $.browser.msie && parseFloat($.browser.version) < 7;//åˆ¤æ–­æ˜¯å¦ie6
            var controlTop = $window.height() - $this.height() - opts.pageHeightJg;
            controlTop = cssfixedsupport ? $window.scrollTop() + controlTop : controlTop;
            var shouldvisible = ($window.scrollTop() >= opts.startline) ? true : false;

            if (shouldvisible) {
                $this.stop().show();
            } else {
                $this.stop().hide();
            }

            $this.css({
                position: cssfixedsupport ? 'absolute' : 'fixed',
                top: controlTop,
                left: controlLeft
            });
        }, 30);

        $(this).click(function (event) {
            $body.stop().animate({ scrollTop: $(opts.targetObg).offset().top }, opts.duration);
            $(this).blur();
            event.preventDefault();
            event.stopPropagation();
        });
    };

    $.fn.goToTop.def = {
        pageWidth: 1920,//é¡µé¢å®½åº¦
        pageWidthJg: 10,//æŒ‰é’®å’Œé¡µé¢çš„é—´éš”è·ç¦»
        pageHeightJg: 200,//æŒ‰é’®å’Œé¡µé¢åº•éƒ¨çš„é—´éš”è·ç¦»
        startline: 30,//å‡ºçŽ°å›žåˆ°é¡¶éƒ¨æŒ‰é’®çš„æ»šåŠ¨æ¡scrollTopè·ç¦»
        duration: 300,//å›žåˆ°é¡¶éƒ¨çš„é€Ÿåº¦æ—¶é—´
        targetObg: "body"//ç›®æ ‡ä½ç½®
    };
})(jQuery);
$(function () {
    $('<a href="javascript:;" class="backToTop"></a>').appendTo("body");
});
//è°ƒç”¨è¿”å›žé¡¶éƒ¨
$(function () {
    $(".backToTop").goToTop({});
    $(window).bind('scroll resize', function () {
        $(".backToTop").goToTop({});
    });
});

//-----------------------------------------------------------è¿”å›žé ‚éƒ¨
//-----------------------------------------------------------è¿”å›žé ‚éƒ¨
//-----------------------------------------------------------è¿”å›žé ‚éƒ¨


//-----------------------------------------------------------æµ®å‹•
//-----------------------------------------------------------æµ®å‹•
//-----------------------------------------------------------æµ®å‹•
$(window).load(function () {
    var $win = $(window),
        $ad = $('#aclose').css('opacity', 0).show(),	// è®“å»£å‘Šå€å¡Šè®Šé€æ˜Žä¸”é¡¯ç¤ºå‡ºä¾†
        _width = $ad.width(),
        _height = $ad.height(),
        _diffY = 210, _diffX = 10,	// è·é›¢å·¦åŠä¸Šæ–¹é‚Šè·
        _moveSpeed = 600;	// ç§»å‹•çš„é€Ÿåº¦

    // å…ˆæŠŠ #abgne_float_ad ç§»å‹•åˆ°å®šé»ž
    $ad.css({
        top: _diffY,	// å¾€ä¸Š
        left: _diffX,	// å¾€å·¦
        opacity: 1
    });

    // å¹«ç¶²é åŠ ä¸Š scroll åŠ resize äº‹ä»¶
    $win.bind('scroll resize', function () {
        var $this = $(this);

        // æŽ§åˆ¶ #abgne_float_ad çš„ç§»å‹•
        $ad.stop().animate({
            top: $this.scrollTop() + _diffY,	// å¾€ä¸Š
            left: $this.scrollLeft() + _diffX	// å¾€å·¦
        }, _moveSpeed);
    }).scroll();	// è§¸ç™¼ä¸€æ¬¡ scroll()

    // é—œé–‰å»£å‘Š
    $('#aclose .aclose').click(function () {
        $ad.hide();
    });
});

$(window).load(function () {
    var $win = $(window),
        $ad = $('#abgne_float_ad').css('opacity', 0).show(),	// è®“å»£å‘Šå€å¡Šè®Šé€æ˜Žä¸”é¡¯ç¤ºå‡ºä¾†
        _width = $ad.width(),
        _height = $ad.height(),
        _diffY = 210, _diffX = 10,	// è·é›¢å³åŠä¸Šæ–¹é‚Šè·
        _moveSpeed = 600;	// ç§»å‹•çš„é€Ÿåº¦

    // å…ˆæŠŠ #abgne_float_ad ç§»å‹•åˆ°å®šé»ž
    $ad.css({
        top: _diffY,	// å¾€ä¸Š
        left: $win.width() - _width - _diffX,
        opacity: 1
    });

    // å¹«ç¶²é åŠ ä¸Š scroll åŠ resize äº‹ä»¶
    $win.bind('scroll resize', function () {
        var $this = $(this);

        // æŽ§åˆ¶ #abgne_float_ad çš„ç§»å‹•
        $ad.stop().animate({
            top: $this.scrollTop() + _diffY,	// å¾€ä¸Š
            left: $this.scrollLeft() + $this.width() - _width - _diffX
        }, _moveSpeed);
    }).scroll();	// è§¸ç™¼ä¸€æ¬¡ scroll()

    // é—œé–‰å»£å‘Š
    $('#abgne_float_ad .abgne_close_ad').click(function () {
        $ad.hide();
    });
});

//-----------------------------------------------------------æµ®å‹•
//-----------------------------------------------------------æµ®å‹•
//-----------------------------------------------------------æµ®å‹•



//-----------------------------------------------------------é«”è‚²
//-----------------------------------------------------------é«”è‚²
//-----------------------------------------------------------é«”è‚²
$('.sp_btn01').hover(function () {
    var $this = $(this);
    var index = $this.index();
    $this.addClass('hover').siblings().removeClass('hover');
    $('.sp_img').hide().eq(index).show();
}).eq(0).trigger('mouseover');

$('.sp_btn01').hover(function () {
    var $this = $(this);
    var index = $this.index();
    $this.addClass('hover').siblings().removeClass('hover');
    $('.live_img').hide().eq(index).show();
}).eq(0).trigger('mouseover');

$('.sp_btn01').hover(function () {
    var $this = $(this);
    var index = $this.index();
    $this.addClass('hover').siblings().removeClass('hover');
    $('.lot_img').hide().eq(index).show();
}).eq(0).trigger('mouseover');

$('.slot_btn01').hover(function () {
    var $this = $(this);
    var index = $this.index();
    $this.addClass('hover').siblings().removeClass('hover');
    $('.slot_img').hide().eq(index).show();
}).eq(0).trigger('mouseover');

$('.vs_btn01').hover(function () {
    var $this = $(this);
    var index = $this.index();
    $this.addClass('hover').siblings().removeClass('hover');
    $('.vs_img01').hide().eq(index).show();
}).eq(0).trigger('mouseover');

$('.vs_btn02').hover(function () {
    var $this = $(this);
    var index = $this.index();
    $this.addClass('hover').siblings().removeClass('hover');
    $('.vs_img02').hide().eq(index).show();
}).eq(0).trigger('mouseover');
//-----------------------------------------------------------é«”è‚²
//-----------------------------------------------------------é«”è‚²
//-----------------------------------------------------------é«”è‚²


