(function() {

    //清除所有input的value
    function ClearValue(forms) {
        this.forms = forms;
        this.load();
    }

    ClearValue.prototype = {
        constructor: this,
        load: function() {
            var _this = this;
            this.forms.each(function() {
                _this.clearValue($(this));
            });
        },
        clearValue: function(fm) {
            this.inputs = $("input.text,input.keyword", fm);
            this.textarea = $("textarea", fm);
            var _this = this;
            var dValues = [];
            var aValues = [];
            this.inputs.each(function(n) {
                dValues[n] = $(_this.inputs[n]).val();
            });
            this.textarea.each(function(n) {
                aValues[n] = $(_this.textarea[n]).html();
            });

            this.inputs.each(function(n) {
                $(this).focus(function() {
                    if ($(this).val() == dValues[n]) {
                        $(this).val("");
                    }
                });
                $(this).blur(function() {
                    if ($(this).val() == "") {
                        $(this).val(dValues[n]);
                    }
                });
            });
            this.textarea.each(function(n) {
                $(this).focus(function() {
                    if ($(this).html() == aValues[n]) {
                        $(this).html("");
                    }
                });
                $(this).blur(function() {
                    if ($(this).html() == "") {
                        $(this).html(aValues[n]);
                    }
                });
            });
        }
    };

    window.ClearValue = ClearValue;
    //清除所有input的value
    //顶端adver渐隐
    function FadeAdver(args) {
        for (var i in args) {
            this[i] = args[i];
        }
        this.speed = args.speed ? args.speed: 5000; //间隔时间默认3秒
        this.sTime = args.sTime ? args.sTime: 1000; //渐进时间，默认1秒
        this.load();
        this.start();
    }

    FadeAdver.prototype = {
        constructor: this,
        load: function() {
            var _this = this;
            this.num = 0; //计时器
            this.mNum = this.num + 1; //轮播计时
            this.len = this.divs.length;

            //所有div设置absolute并排好index
            this.divs.each(function(num) {
                var z_index = 500 - num;
                $(this).css({
                    "position": "absolute",
                    "left": 0,
                    "top": 0,
                    "z-index": z_index,
                    "display": "none"
                })
            });

            $(this.divs[0]).show();

            //所有div设置absolute并排好index

            this.btns.each(function(num) {
                $(this).mouseover(function() {
                    _this.show(num);
                    _this.stop();
                }).mouseout(function() {
                    _this.start();
                });
            });

            //左右按钮的使用
            if ( !! this.preBtn && !!this.nextBtn) {
                this.preBtn.css("z-index", 1000);
                this.preBtn.click(function() {
                    var num = _this.num - 1;
                    if (num < 0) {
                        num = _this.len - 1;
                    }
                    _this.show(num);
                });
                this.nextBtn.css("z-index", 1000);
                this.nextBtn.click(function() {
                    var num = _this.num + 1;
                    if (num >= _this.len) {
                        num = 0;
                    }
                    _this.show(num);
                });
            }

            this.divs.each(function(num) {
                $(this).mouseover(function() {
                    _this.stop();
                }).mouseout(function() {
                    _this.start();
                });
            });
        },
        show: function(num) {
            if (num == this.num) return; //同一个返回

            var _this = this;
            this.flag = false; //关闭控制开关
            this.btns.each(function(i) {
                if (i == num) {
                    $(this).addClass("hover");
                } else {
                    $(this).removeClass("hover");
                }
            });

            $(this.divs[this.num]).fadeOut(this.sTime); //旧的淡出
            $(this.divs[num]).fadeIn(_this.sTime); //新的淡入
            _this.num = num;
            _this.mNum = num + 1;

        },
        start: function() {
            var _this = this;
            this.interval = setInterval(function() {
                if (_this.mNum >= _this.len) {
                    _this.mNum = 0;
                }
                _this.show(_this.mNum);
            },
            this.speed);
        },
        stop: function() {
            clearInterval(this.interval);
        }
    };

    window.FadeAdver = FadeAdver;
    //顶端adver	
    //ChangeDiv切换效果
    function ChangeDiv(args) {
        for (var i in args) {
            this[i] = args[i];
        }
        this.type = this.type ? this.type: "click"; // mouseover 改为click
        this.load();
    }

    ChangeDiv.prototype = {
        constructor: this,
        load: function() {
            var _this = this;
            this.btns.each(function(num) {
                if (_this.type == "click") {
                    $(this).click(function() {
                        _this.change(num)
                    });
                } else {
                    $(this).mouseover(function() {
                        _this.change(num)
                    });
                }
            });
        },
        change: function(num) {
            var _this = this;

            this.btns.each(function(n) {
                if (n == num) {
                    $(this).addClass("hover");
                } else {
                    $(this).removeClass("hover");
                }
            });

            this.divs.each(function(n) {
                if (n == num) {
                    $(this).addClass("show");
                } else {
                    $(this).removeClass("show");
                }
            });
        }
    };

    window.ChangeDiv = ChangeDiv;
    //ChangeDiv切换效果
    //select 替换类
    function SameSelect(obj) {
        this.obj = obj;
        this.opts = $("option", obj);
        this.top = $(".top", obj);
        this.btn = $(".btn", obj);
        this.lis = $("li", obj);
        this.load();
    }

    SameSelect.prototype = {
        constructor: this,
        load: function() {
            var _this = this;

            this.btn.click(function() {
                if (_this.obj.hasClass("select_hover")) {
                    _this.hide();
                } else {
                    _this.show();
                }
            });
            this.lis.mouseover(function() {
                _this.lis.removeClass("hover");
                $(this).addClass("hover");
            });
            this.lis.each(function(num) {
                $(this).click(function() {
                    _this.set(num);
                });
            });
            this.obj.mouseout(function() {
                _this.wait = setTimeout(function() {
                    _this.hide();
                },
                200);
            });
            $("*", this.obj).mouseover(function() {
                if ( !! _this.wait) {
                    clearTimeout(_this.wait);
                }
            });
        },
        show: function() {
            var _this = this;
            //和top相同的li隐藏一下
            this.lis.show();
            this.lis.each(function() {
                if ($(this).html() == _this.top.html()) {
                    $(this).hide();
                }
            });

            this.obj.addClass("select_hover");
        },
        hide: function() {
            this.obj.removeClass("select_hover");
        },
        set: function(num) {
            var _this = this;
            this.hide();
            this.top.html($(this.lis[num]).html());
            this.opts.removeAttr("selected");
            $(this.opts[num]).attr("selected", "selected");
        }
    };

    window.SameSelect = SameSelect;
    //select 替换类
})();

$(function() {

    new ClearValue($("form")); //清除默认文字
    //tabs统一调用
    $(".tabs").each(function() {
        new ChangeDiv({
            btns: $(".tabs_handle .h", this),
            divs: $(".con", this)
        });
    });

    //select美化的统一调用
    $(".select").each(function() {
        new SameSelect($(this));
    });
    $(".ui_select").each(function() {
        new SameSelect($(this));
    });

    //返回顶部
    $(".flog_left .go_top,.flog_right .go_top").click(function(e) {
        $(window).scrollTop(0);
        if (e == null) var e = window.event;
        e.cancelBubble = true;
        if (e.stopPropagation) e.stopPropagation();
    });

    //top_login密码切换
    $(".top_login .ps1").focus(function() {
        $(this).hide();
        $(".top_login .ps2").show().focus();
    });
    $(".top_login .ps2").blur(function() {
        if ($(this).val() == "") {
            $(this).hide();
            $(".top_login .ps1").show();
        }
    });

    //nav下拉菜单特效
    if ($.browser.msie && ($.browser.version == "6.0") && !$.support.style) {
        $(".nav_slide").css("width", $(window).width());
        $(".nav_slide").css("background-image", "url(images/nav_slide.png)");
    }

    //站内信超过9条显示n
    if ($(".afi_user a").html() * 1 > 9) {
        $(".afi_user a").html("N");
    }

    $(".nav_in").hover(function() {
        $(this).addClass("nav_in_hover");
        $(".nav_slide", this).fadeIn(200);
    },
    function() {
        $(".nav_slide", this).fadeOut(200);
        $(this).removeClass("nav_in_hover");
    });

    $(".nav_slide1 .list").hover(function() {
        $(this).addClass("list_hover");
    },
    function() {
        $(this).removeClass("list_hover");
    });

});

/* ========================================================================
 * Bootstrap: transition.js v3.3.4
 * http://getbootstrap.com/javascript/#transitions
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

+
function($) {
    'use strict';

    // CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
    // ============================================================
    function transitionEnd() {
        var el = document.createElement('bootstrap')

        var transEndEventNames = {
            WebkitTransition: 'webkitTransitionEnd',
            MozTransition: 'transitionend',
            OTransition: 'oTransitionEnd otransitionend',
            transition: 'transitionend'
        }

        for (var name in transEndEventNames) {
            if (el.style[name] !== undefined) {
                return {
                    end: transEndEventNames[name]
                }
            }
        }

        return false // explicit for ie8 (  ._.)
    }

    // http://blog.alexmaccaw.com/css-transitions
    $.fn.emulateTransitionEnd = function(duration) {
        var called = false;
        var $el = this;
        $(this).one('bsTransitionEnd', function() {
            called = true;
        });
        var callback = function() {
            if (!called) $($el).trigger($.support.transition.end)
        }
        setTimeout(callback, duration);
        return this;
    }

    $(function() {
        $.support.transition = transitionEnd()

        if (!$.support.transition) return

        $.event.special.bsTransitionEnd = {
            bindType: $.support.transition.end,
            delegateType: $.support.transition.end,
            handle: function(e) {
                if ($(e.target).is(this)) return e.handleObj.handler.apply(this, arguments)
            }
        }
    })

} (jQuery);

/* ========================================================================
 * Bootstrap: tab.js v3.3.2
 * http://getbootstrap.com/javascript/#tabs
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

+
function($) {
    'use strict';

    // TAB CLASS DEFINITION
    // ====================
    var Tab = function(element) {
        this.element = $(element);
    }

    Tab.VERSION = '3.3.2';

    Tab.TRANSITION_DURATION = 150;

    Tab.prototype.show = function() {
        var $this = this.element
        var $ul = $this.closest('ul:not(.dropdown-menu)');
        var selector = $this.data('target');

        if (!selector) {
            selector = $this.attr('href');
            selector = selector && selector.replace(/.*(?=#[^\s]*$)/, ''); // strip for ie7
        }

        if ($this.parent('li').hasClass('active')) return

        var $previous = $ul.find('.active:last a');
        var hideEvent = $.Event('hide.bs.tab', {
            relatedTarget: $this[0]
        });
        var showEvent = $.Event('show.bs.tab', {
            relatedTarget: $previous[0]
        });

        $previous.trigger(hideEvent);
        $this.trigger(showEvent);

        if (showEvent.isDefaultPrevented() || hideEvent.isDefaultPrevented()) return

        var $target = $(selector);

        this.activate($this.closest('li'), $ul);
        this.activate($target, $target.parent(), function() {
            $previous.trigger({
                type: 'hidden.bs.tab',
                relatedTarget: $this[0]
            });
            $this.trigger({
                type: 'shown.bs.tab',
                relatedTarget: $previous[0]
            });
        });
    }

    Tab.prototype.activate = function(element, container, callback) {
        var $active = container.find('> .active');
        var transition = callback && $.support.transition && (($active.length && $active.hasClass('fade')) || !!container.find('> .fade').length)

        function next() {
            $active.removeClass('active').find('> .dropdown-menu > .active').removeClass('active').end().find('[data-toggle="tab"]').attr('aria-expanded', false)

            element.addClass('active').find('[data-toggle="tab"]').attr('aria-expanded', true)

            if (transition) {
                element[0].offsetWidth; // reflow for transition
                element.addClass('in');
            } else {
                element.removeClass('fade');
            }

            if (element.parent('.dropdown-menu').length) {
                element.closest('li.dropdown').addClass('active').end().find('[data-toggle="tab"]').attr('aria-expanded', true);
            }

            callback && callback();
        }

        $active.length && transition ? $active.one('bsTransitionEnd', next).emulateTransitionEnd(Tab.TRANSITION_DURATION) : next()

        $active.removeClass('in');
    }

    // TAB PLUGIN DEFINITION
    // =====================
    function Plugin(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data('bs.tab');

            if (!data) $this.data('bs.tab', (data = new Tab(this)));
            if (typeof option == 'string') data[option]()
        });
    }

    var old = $.fn.tab;

    $.fn.tab = Plugin;
    $.fn.tab.Constructor = Tab;

    // TAB NO CONFLICT
    // ===============
    $.fn.tab.noConflict = function() {
        $.fn.tab = old;
        return this;
    }

    // TAB DATA-API
    // ============
    var clickHandler = function(e) {
        e.preventDefault(); 
        Plugin.call($(this), 'show');
    }

    $(document).on('click.bs.tab.data-api', '[data-toggle="tab"]', clickHandler).on('click.bs.tab.data-api', '[data-toggle="pill"]', clickHandler);

} (jQuery);

/* ========================================================================
 * Bootstrap: modal.js v3.3.5
 * http://getbootstrap.com/javascript/#modals
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

+
function($) {
    'use strict';

    // MODAL CLASS DEFINITION
    // ======================
    var Modal = function(element, options) {
        this.options = options,
        this.$body = $(document.body),
        this.$element = $(element), 
        this.$dialog = this.$element.find('.modal-dialog'),
        this.$backdrop = null,
        this.isShown = null,
        this.originalBodyPad = null,
        this.scrollbarWidth = 0,
        this.ignoreBackdropClick = false
 
        if (this.options.remote) {
            this.$element.find('.modal-content').load(this.options.remote, $.proxy(function() {
                this.$element.trigger('loaded.bs.modal');
            },
            this));
        }
    }

    Modal.VERSION = '3.3.5';

    Modal.TRANSITION_DURATION = 300;
    Modal.BACKDROP_TRANSITION_DURATION = 150;

    Modal.DEFAULTS = {
        backdrop: true,
        keyboard: true,
        show: true
    }

    Modal.prototype.toggle = function(_relatedTarget) {
        return this.isShown ? this.hide() : this.show(_relatedTarget)
    }

    Modal.prototype.show = function(_relatedTarget) {
        var that = this;
        var e = $.Event('show.bs.modal', {
            relatedTarget: _relatedTarget
        });

        this.$element.trigger(e);

        if (this.isShown || e.isDefaultPrevented()) 
            return;

        this.isShown = true;

        this.checkScrollbar();
        this.setScrollbar();
        this.$body.addClass('modal-open');

        this.escape();
        this.resize();

        this.$element.on('click.dismiss.bs.modal', '[data-dismiss="modal"]', $.proxy(this.hide, this))

        this.$dialog.on('mousedown.dismiss.bs.modal',
        function() {
            that.$element.one('mouseup.dismiss.bs.modal',
            function(e) {
                if ($(e.target).is(that.$element)) that.ignoreBackdropClick = true
            });
        })

        this.backdrop(function() {
            var transition = $.support.transition && that.$element.hasClass('fade')

            if (!that.$element.parent().length) {
                that.$element.appendTo(that.$body) // don't move modals dom position
            }

            that.$element.show().scrollTop(0);
            that.adjustDialog();

            if (transition) {
                that.$element[0].offsetWidth // force reflow
            }

            that.$element.addClass('in');
            that.enforceFocus();

            var e = $.Event('shown.bs.modal', {
                relatedTarget: _relatedTarget
            });

            transition ? that.$dialog // wait for modal to slide in
            .one('bsTransitionEnd',
            function() {
                that.$element.trigger('focus').trigger(e)
            }).emulateTransitionEnd(Modal.TRANSITION_DURATION) : that.$element.trigger('focus').trigger(e)
        })
    }

    Modal.prototype.hide = function(e) {
        if (e) 
            e.preventDefault();

        e = $.Event('hide.bs.modal')

        this.$element.trigger(e);

        if (!this.isShown || e.isDefaultPrevented()) 
            return;

        this.isShown = false;

        this.escape();
        this.resize();

        $(document).off('focusin.bs.modal');

        this.$element.removeClass('in').off('click.dismiss.bs.modal').off('mouseup.dismiss.bs.modal');

        this.$dialog.off('mousedown.dismiss.bs.modal');

        $.support.transition && this.$element.hasClass('fade') ? this.$element.one('bsTransitionEnd', $.proxy(this.hideModal, this)).emulateTransitionEnd(Modal.TRANSITION_DURATION) : this.hideModal()
    }

    Modal.prototype.enforceFocus = function() {
        $(document).off('focusin.bs.modal') // guard against infinite focus loop
        .on('focusin.bs.modal', $.proxy(function(e) {
            if (this.$element[0] !== e.target && !this.$element.has(e.target).length) {
                this.$element.trigger('focus')
            }
        },
        this))
    }

    Modal.prototype.escape = function() {
        if (this.isShown && this.options.keyboard) {
            this.$element.on('keydown.dismiss.bs.modal', $.proxy(function(e) {
                e.which == 27 && this.hide()
            },
            this))
        } else if (!this.isShown) {
            this.$element.off('keydown.dismiss.bs.modal')
        }
    }

    Modal.prototype.resize = function() {
        if (this.isShown) {
            $(window).on('resize.bs.modal', $.proxy(this.handleUpdate, this))
        } else {
            $(window).off('resize.bs.modal')
        }
    }

    Modal.prototype.hideModal = function() {
        var that = this;
        this.$element.hide();
        this.backdrop(function() {
            that.$body.removeClass('modal-open');
            that.resetAdjustments();
            that.resetScrollbar();
            that.$element.trigger('hidden.bs.modal');
        })
    }

    Modal.prototype.removeBackdrop = function() {
        this.$backdrop && this.$backdrop.remove();
        this.$backdrop = null;
    }

    Modal.prototype.backdrop = function(callback) {
        var that = this;
        var animate = this.$element.hasClass('fade') ? 'fade': ''

        if (this.isShown && this.options.backdrop) {
            var doAnimate = $.support.transition && animate

            this.$backdrop = $(document.createElement('div')).addClass('modal-backdrop ' + animate).appendTo(this.$body)

            this.$element.on('click.dismiss.bs.modal', $.proxy(function(e) {
                if (this.ignoreBackdropClick) {
                    this.ignoreBackdropClick = false;
                    return;
                }
                if (e.target !== e.currentTarget) 
                    return this.options.backdrop == 'static' ? this.$element[0].focus() : this.hide()
            },
            this));

            if (doAnimate) 
                this.$backdrop[0].offsetWidth // force reflow
                this.$backdrop.addClass('in')

            if (!callback) 
                return;

            doAnimate ? this.$backdrop.one('bsTransitionEnd', callback).emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION) : callback()

        } else if (!this.isShown && this.$backdrop) {
            this.$backdrop.removeClass('in');

            var callbackRemove = function() {
                that.removeBackdrop();
                callback && callback()
            }
            $.support.transition && this.$element.hasClass('fade') ? this.$backdrop.one('bsTransitionEnd', callbackRemove).emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION) : callbackRemove()

        } else if (callback) {
            callback();
        }
    }

    // these following methods are used to handle overflowing modals
    Modal.prototype.handleUpdate = function() {
        this.adjustDialog()
    }

    Modal.prototype.adjustDialog = function() {
        var modalIsOverflowing = this.$element[0].scrollHeight > document.documentElement.clientHeight

        this.$element.css({
            paddingLeft: !this.bodyIsOverflowing && modalIsOverflowing ? this.scrollbarWidth: '',
            paddingRight: this.bodyIsOverflowing && !modalIsOverflowing ? this.scrollbarWidth: ''
        })
    }

    Modal.prototype.resetAdjustments = function() {
        this.$element.css({
            paddingLeft: '',
            paddingRight: ''
        })
    }

    Modal.prototype.checkScrollbar = function() {
        var fullWindowWidth = window.innerWidth
        if (!fullWindowWidth) { // workaround for missing window.innerWidth in IE8
            var documentElementRect = document.documentElement.getBoundingClientRect();
            fullWindowWidth = documentElementRect.right - Math.abs(documentElementRect.left)
        }
        this.bodyIsOverflowing = document.body.clientWidth < fullWindowWidth; 
        this.scrollbarWidth = this.measureScrollbar();
    }

    Modal.prototype.setScrollbar = function() {
        var bodyPad = parseInt((this.$body.css('padding-right') || 0), 10);
        this.originalBodyPad = document.body.style.paddingRight || '';
        if (this.bodyIsOverflowing) 
            this.$body.css('padding-right', bodyPad + this.scrollbarWidth);
    }

    Modal.prototype.resetScrollbar = function() {
        this.$body.css('padding-right', this.originalBodyPad);
    }

    Modal.prototype.measureScrollbar = function() { // thx walsh
        var scrollDiv = document.createElement('div');
        scrollDiv.className = 'modal-scrollbar-measure';
        this.$body.append(scrollDiv);
        var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth;
        this.$body[0].removeChild(scrollDiv);
        return scrollbarWidth;
    }

    // MODAL PLUGIN DEFINITION
    // =======================
    function Plugin(option, _relatedTarget) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data('bs.modal');
            var options = $.extend({}, Modal.DEFAULTS, $this.data(), typeof option == 'object' && option);

            if (!data) $this.data('bs.modal', (data = new Modal(this, options))) 
                if (typeof option == 'string') 
                    data[option](_relatedTarget);
            else if (options.show) data.show(_relatedTarget)
        })
    }

    var old = $.fn.modal;
    $.fn.modal = Plugin;
    $.fn.modal.Constructor = Modal;

    // MODAL NO CONFLICT
    // =================
    $.fn.modal.noConflict = function() {
        $.fn.modal = old;
        return this;
    }

    // MODAL DATA-API
    // ==============
    $(document).on('click.bs.modal.data-api', '[data-toggle="modal"]',
    function(e) {
        var $this = $(this);
        var href = $this.attr('href');
        var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) // strip for ie7
        var option = $target.data('bs.modal') ? 'toggle': $.extend({
            remote: !/#/.test(href) && href
        },
        $target.data(), $this.data())

        if ($this.is('a'))
            e.preventDefault();

        $target.one('show.bs.modal',
        function(showEvent) {
            if (showEvent.isDefaultPrevented()) return // only register focus restorer if modal will actually get shown
            $target.one('hidden.bs.modal',
            function() {
                $this.is(':visible') && $this.trigger('focus');
            });
        });
        Plugin.call($target, option, this);
    })

} (jQuery);

/**========================================================================
 * jquery 扩展方法
 * $.ajaxLoad.open()  打开遮罩层
 * $.ajaxLoad.close() 关闭遮罩层
 * ========================================================================*/
+
function($) {

    var $backdrop = null;
    var $body = $(document.body);
    var $child = $(document.createElement('div')).addClass('ui_loading').html('<span class="ui_icon_loading"></span>加载中，请稍候...')

    $.extend({
        ajaxLoad: {
            open: function() {
                if ($(document.body).children('.ui_modal_backdrop').length == 0) {
                    $backdrop = $(document.createElement('div')).addClass('ui_modal_backdrop').append($child).appendTo($(document.body))
                }
                //console.log('open')
            },
            close: function() {
                $backdrop && $backdrop.remove()
                //console.log('close')
            }
        }
    })

} (jQuery);

/**========================================================================
 * ajaxForm, 全局的ajax请求函数，在请求前打开遮罩层，请求后关闭遮罩层，并且失败时弹出状态码
 * @param method
 * @param [url]	可选，请求的地址
 * @param [data] 可选，请求数据
 * @param [callback] 可选，成功的回调函数
 * @param [type] 可选，返回的数据类型
 * ========================================================================*/
+
function($) {
    $.extend({
        ajaxForm: function(method, url, data, callback, type) {
            if ($.isFunction(data)) {
                type = type || callback;
                callback = data;
                data = undefined;
            }

            $.ajax({
                url: url,
                type: method,
                dataType: type,
                data: data,
                cache: false,
                success: callback,
                beforeSend: $.ajaxLoad.open(),
                complete: $.ajaxLoad.close(),
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    alert('系统繁忙！')
                }
            });

        }
    });

} (jQuery);

/*onload 事件绑定
* ==============*/
+
function($) {

    $(function() {
        var $doc = $(document)
        /*限制只能输入整数
		 * =============*/
        $doc.on('keyup', 'input[data-rule-digits]',
        function() {
            this.value = this.value.replace(/\D|^0/g, "")
        })
        /*限制只能输入数字，可以为小数
		 * =============*/
        $doc.on('keyup', 'input[data-rule-num]',
        function() {

            if (!/^\d+[.]?\d*$/.test(this.value)) {
                this.value = /^\d+[.]?\d*/.exec(this.value);
            }
            return false;
        })

    })

} (jQuery);

var Homepage = {};
/* 滚动条
* ====*/
Homepage.scrollTo = function(pos, speed) {
    pos = pos || 0;
    speed = speed || 600;
    $('html,body').stop(true).animate({
        scrollTop: pos
    },
    speed);
};