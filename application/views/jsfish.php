<script type="text/javascript">
	function filter_fish(code) {

		if (code == "JDB") {
			$("#JDB").addClass("active").fadeOut(0).fadeIn(1000);
			$('#SG').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#JILI').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#FC').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#RSG').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#CQ9').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#PS').removeClass("active").fadeOut(0).fadeIn(1000);
		} else if (code == "SG") {
			$("#SG").addClass("active").fadeOut(0).fadeIn(1000);
			$('#JDB').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#JILI').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#FC').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#RSG').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#CQ9').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#PS').removeClass("active").fadeOut(0).fadeIn(1000);

		} else if (code == "JILI") {
			$("#JILI").addClass("active").fadeOut(0).fadeIn(1000);
			$('#SG').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#JDB').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#FC').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#RSG').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#CQ9').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#PS').removeClass("active").fadeOut(0).fadeIn(1000);
		} else if (code == "FC") {
			$("#FC").addClass("active").fadeOut(0).fadeIn(1000);
			$('#SG').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#JILI').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#JDB').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#RSG').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#CQ9').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#PS').removeClass("active").fadeOut(0).fadeIn(1000);
		} else if (code == "RSG") {
			$("#RSG").addClass("active").fadeOut(0).fadeIn(1000);
			$('#SG').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#JILI').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#FC').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#JDB').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#CQ9').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#PS').removeClass("active").fadeOut(0).fadeIn(1000);
		} else if (code == "CQ9") {
			$("#CQ9").addClass("active").fadeOut(0).fadeIn(1000);
			$('#SG').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#JILI').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#FC').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#RSG').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#JDB').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#PS').removeClass("active").fadeOut(0).fadeIn(1000);
		} else{
			$("#PS").addClass("active").fadeOut(0).fadeIn(1000);
			$('#SG').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#JILI').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#FC').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#RSG').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#CQ9').removeClass("active").fadeOut(0).fadeIn(1000);
			$('#JDB').removeClass("active").fadeOut(0).fadeIn(1000);
		}	
	}
</script>