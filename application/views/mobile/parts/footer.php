<nav class="navbar fixed-bottom navbar-light">
	<div class="container-fluid content">
		<a href="<?php echo site_url('home')?>" class="nav-item <?php if($this->uri->segment(1)=="home"){echo "active";} ?>">
			<div class="img">
				<img src="<?php echo base_url('assets/mobile/img/navbar_icon_home_off.png') ?>" alt="" />
				<img class="active" src="<?php echo base_url('assets/mobile/img/navbar_icon_home_on.png') ?>" alt="" />
			</div>
			<div class="text"><?php echo $this->lang->line('label_home'); ?></div>
		</a>
		<a href="<?php echo site_url('account/wallet') ?>" class="nav-item <?php if($this->uri->segment(1)=="account"&&$this->uri->segment(2)=="wallet"){echo "active";} ?>">
			<div class="img">
				<img src="<?php echo base_url('assets/mobile/img/navbar_icon_wallet_off.png') ?>" alt="" />
				<img class="active" src="<?php echo base_url('assets/mobile/img/navbar_icon_wallet_on.png') ?>" alt="" />
			</div>
			<div class="text"><?php echo $this->lang->line('label_wallet'); ?></div>
		</a>
		<a href="<?php echo site_url('promotion') ?>" class="nav-item <?php if($this->uri->segment(1)=="promotion"){echo "active";} ?>">
			<div class="img">
				<img src="<?php echo base_url('assets/mobile/img/navbar_icon_promo_off.png') ?>" alt="" />
				<img class="active" src="<?php echo base_url('assets/mobile/img/navbar_icon_promo_on.png') ?>" alt="" />
			</div>
			<div class="text"><?php echo $this->lang->line('label_promotion'); ?></div>
		</a>

		<a href="<?php echo site_url('account') ?>" class="nav-item <?php if($this->uri->segment(1)=="account"&&$this->uri->segment(2)!="wallet"){echo "active";} ?>">
			<div class="img">
				<img src="<?php echo base_url('assets/mobile/img/navbar_icon_myacc_off.png') ?>" alt="" />
				<img class="active" src="<?php echo base_url('assets/mobile/img/navbar_icon_myacc_on.png') ?>" alt="" />
			</div>
			<div class="text"><?php echo $this->lang->line('label_my_account'); ?></div>
		</a>
	</div>
</nav>
<a style="display: none;" id="launch_game"></a>
<a style="display: none;" id="launch_payment_gateway"></a>
<?php $this->load->view('jsfile'); ?>
<?php $this->load->view('jscode'); ?>

<script>
	$(function() {
		$(".menu_open").click(function() {
			$(".menu_open").fadeOut();
			//$(".menu_close1").fadeIn();
			$(".menu_close2").fadeIn();
			//$(".menu_close1").rotate({ animateTo: 45 });
			$(".menu_close2").rotate({
				animateTo: 360
			});
			$(".menu-mo-box").fadeIn();
		});

		$(".menu_close2").click(function() {
			$(".menu_open").fadeIn();
			//$(".menu_close1").fadeOut();
			$(".menu_close2").fadeOut();
			//$(".menu_close1").rotate({ animateTo: 0 });
			$(".menu_close2").rotate({
				animateTo: 0
			});
			$(".menu-mo-box").fadeOut();
		});
	});
</script>

<script>
	$(function() {
		//Get_AgentTopTen();

		$(".sp_btn01").click(
			function() {
				//$('.sp_btn01').removeClass("active");
				//$(this).addClass("active");

				$('.menugames').removeClass('active');

				$('.sp_img').css('display', 'none');
				$('.menu_game_div0').css('display', 'none');
				if ($(this).hasClass("menu_game0")) {
					//$('.menu_game_div0').css('display', 'flex');
					$('.menu_game_div0').css('display', 'block');
					$('.menu_game0_img').addClass('active');
				} else if ($(this).hasClass("menu_game1")) {
					$('.menu_game_div1').css('display', 'block');
					$('.menu_game1_img').addClass('active');
				} else if ($(this).hasClass("menu_game2")) {
					$('.menu_game_div2').css('display', 'block');
					$('.menu_game2_img').addClass('active');
				} else if ($(this).hasClass("menu_game3")) {
					$('.menu_game_div3').css('display', 'block');
					$('.menu_game3_img').addClass('active');
				} else if ($(this).hasClass("menu_game4")) {
					$('.menu_game_div4').css('display', 'block');
					$('.menu_game4_img').addClass('active');
				} else if ($(this).hasClass("menu_game5")) {
					$('.menu_game_div5').css('display', 'block');
					$('.menu_game5_img').addClass('active');
				}

			}
		);
	});
</script>

<!-- Start of LiveChat (www.livechatinc.com) code -->
<!-- <script>
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
<noscript><a href="https://www.livechatinc.com/chat-with/13335741/" rel="nofollow">Chat with us</a>, powered by <a href="https://www.livechatinc.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript> -->
<!-- End of LiveChat code -->

</body>

</html>