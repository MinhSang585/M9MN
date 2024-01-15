<script type="text/javascript">
	function filter_board(code) {

		if (code == "KM") {
			$("#KM").addClass("active").fadeOut(0).fadeIn(1000);
			$('#V8').removeClass("active").fadeOut(0).fadeIn(1000);
		} else{
			$("#V8").addClass("active").fadeOut(0).fadeIn(1000);
			$('#KM').removeClass("active").fadeOut(0).fadeIn(1000);
		}	
	}
</script>