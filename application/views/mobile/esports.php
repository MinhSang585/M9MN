<?php $this->load->view('mobile/parts/header');?>
	
	<div class="row no-gutters text-center mb-5 pb-3">
		<?php
			$html = '';
			for($i=0;$i<sizeof($game);$i++)
			{
				$game_image = '';
				switch($game[$i]['game_code'])
				{
					case 'LH': $game_image = 'esports04.png'; break;
				}
				
				if( ! empty($game_image))
				{
					$html .= '<div class="col-10 p-3 m-auto"><img onclick="open_game(\'' . $game[$i]['game_code'] . '\', \'' . GAME_ESPORTS . '\')" src="' . base_url('assets/data/1543/uploads/new/' . $game_image) . '" class="d-block w-100" /></div>';
				}
			}

			echo $html;
		?>
		<div class="col-12 p-3 text-white">
			<hr />
			MVIP888 Casino - ESports Betting Malaysia
			<hr />
		</div>
		<div class="col-12 p-3 text-white text-left">
			<strong>MVIP888 casino</strong> provide customers with a huge range of betting opportunities with all sports covered including E-Sports, Football, Racing, Tennis, Cricket and Basketball. Bet on a host of pre-match and In-Play markets on every live <strong> Premier League, Champions League, World Cup and Euro Cup.</strong>  Enjoy all the exciting International Cricket action and take advantage of numerous betting opportunities on Grand Slam Tennis. You can also access the same huge range of markets and events on your mobile or tablet, through MVIP888. We even offer maximum betting of up to $50,000.
		</div>
	</div>
	
<?php $this->load->view('mobile/parts/footer');?>