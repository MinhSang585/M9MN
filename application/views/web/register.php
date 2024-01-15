<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo get_language_code('iso'); ?>">
<head>
    <title><?php echo (isset($seo['page_title']) ? $seo['page_title'] : ''); ?></title>
    <link type="text/css" href="<?php echo base_url('assets/general/boostrap5/bootstrap.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/plugin/fontawesome-free/css/all.min.css'); ?>">
    <link type="text/css" href="<?php echo base_url('assets/desktop/css/master/master.css?=id123'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('assets/desktop/css/modaltransfer.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('assets/desktop/css/mainheader.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('assets/layer/custom.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('assets/desktop/css/iziModal.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('assets/desktop/css/infoNew.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/js/datatables/datatables.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/css/reveal.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/css/jquery-ui.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/css/jquery.datetimepicker.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ebv2/css/font-awesome.css'); ?>">
    <link type="text/css" href="<?php echo base_url('assets/desktop/css/general.css?id=' . time()); ?>" rel="stylesheet">
    <?php $this->load->view('meta'); ?>
    <!-- Desktop -->
    <link type="text/css" href="<?php echo base_url('assets/desktop/css/dbg_style.css?id=' . time()); ?>" rel="stylesheet">
</head>
<body>
    <section class="main login">
        <div class="login__container">
            <div class="login__container__content">
                <div class="login__container__content__top">
                    <div class="login__container__content__top__logo">
                        <img src="<?php echo base_url('assets/desktop/images/web_logo.jpg'); ?>">
                    </div>
                </div>
                <?php echo form_open('ajax/register', array('id' => 'sign-up-form', 'autocomplete' => 'off', 'class' => 'login__container__content__form')); ?>
                <div id="display_db_error" name="display_db_error_reg" class="general_err"></div>
                <div id="step1form">
                    <div class="input-group mt-3">
                        <span class="input-group-text">
                            <img src="<?php echo base_url('assets/desktop/images/signup_icon_username.png'); ?>" />
                        </span>
                        <input type="text" class="form-control" id="fr_username" name="username" placeholder="Username" maxlength="10" value="" autocapitalize="none" autocomplete="off" />
                    </div>
                    <div class="input-group mt-3">
                        <span class="input-group-text">
                            <img src="<?php echo base_url('assets/desktop/images/signup_icon_password.png'); ?>" />
                        </span>
                        <input type="password" class="form-control" id="fr_new_password" name="password" maxlength="15" placeholder="Password" value="" />
                    </div>
                    <div class="input-group mt-3">
                        <span class="input-group-text">
                            <img src="<?php echo base_url('assets/desktop/images/signup_icon_password.png'); ?>" />
                        </span>
                        <input type="password" class="form-control" name="passconf" id="passconf" maxlength="15" placeholder="Confirm Password" />
                    </div>
                </div>
                <div id="step2form">
                    <div class="input-group mt-3">
                        <span class="input-group-text">
                            <img src="<?php echo base_url('assets/desktop/images/signup_icon_mobile.png'); ?>" />
                        </span>
                        <input type="number" class="form-control" name="mobile" id="fr_contact_number" maxlength="20" placeholder="Contact Number" />
                    </div>
                    <div class="input-group mt-3">
                        <span class="input-group-text">
                            <img src="<?php echo base_url('assets/desktop/images/signup_icon_verify.png'); ?>" />
                        </span>
                        <input type="text" class="form-control" id="fr_gdcode" name="captcha" maxlength="4" placeholder="Code" autocomplete="off" />
                        <span class="input-group-text">
                            <a href="javascript:void(0)" class="clearfix">
                                <img id="captcha" src="<?php echo site_url('ajax/captcha');?>" height="34" border="0" align="left" style="width: 85px">
                            </a>
                        </span>
                    </div>
                    <div class="dont-have-an-account">
                        <div>
                            <div class="mb-1"><?php echo $this->lang->line('label_already_have_an_account'); ?></div>
                            <a href="<?php echo site_url('home')?>"><?php echo $this->lang->line('label_login_here'); ?></a>
                        </div>
                        <a href="<?php echo site_url('home')?>"><?php echo $this->lang->line('label_i_do_this_later'); ?></a>
                    </div>
                    <input type="hidden" name="fr_action" id="fr_action" value="add_mem">
                    <div class="login-btn mt-5">
                        <button type="submit" class="btn btn-primary" ><?php echo $this->lang->line('label_register'); ?></button>
                    </div>
                </div>
                <?php /*
                <a href="javascript:void(0);" onclick="LC_API.open_chat_window();" class="customer-service">
                    <img src="<?php echo base_url('assets/desktop/images/signup_icon_customer.png'); ?>" />
                    <span>Contact Customer Service</span>
                </a>
                */ ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </section>

    <a style="display: none;" id="launch_game" target="_blank"></a>
    <a style="display: none;" id="launch_payment_gateway" target="_blank"></a>
    <?php $this->load->view('jsfile'); ?>
    <?php $this->load->view('jscode'); ?>
    <!-- Start of LiveChat (www.livechatinc.com) code -->
    <script>
        window.__lc = window.__lc || {};
        window.__lc.license = 13335741;;
        (function(n, t, c) {
            function i(n) {
                return e._h ? e._h.apply(null, n) : e._q.push(n)
            }
            var e = {
                _q: [],
                _h: null,
                _v: "2.0",
                on: function() {
                    i(["on", c.call(arguments)])
                },
                once: function() {
                    i(["once", c.call(arguments)])
                },
                off: function() {
                    i(["off", c.call(arguments)])
                },
                get: function() {
                    if (!e._h) throw new Error("[LiveChatWidget] You can't use getters before load.");
                    return i(["get", c.call(arguments)])
                },
                call: function() {
                    i(["call", c.call(arguments)])
                },
                init: function() {
                    var n = t.createElement("script");
                    n.async = !0, n.type = "text/javascript", n.src = "https://cdn.livechatinc.com/tracking.js", t.head.appendChild(n)
                }
            };
            !n.__lc.asyncInit && e.init(), n.LiveChatWidget = n.LiveChatWidget || e
        }(window, document, [].slice))
    </script>
    <noscript><a href="https://www.livechatinc.com/chat-with/13335741/" rel="nofollow">Chat with us</a>, powered by <a href="https://www.livechatinc.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript>
    <!-- End of LiveChat code -->
</body>

</html>