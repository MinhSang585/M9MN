(function($) {
	var pagify = {
		items: {},
		container: null,
		totalPages: 1,
		perPage: 3,
		currentPage: 0,
		createNavigation: function() {
			this.totalPages = Math.ceil(this.items.length / this.perPage);
			console.log("totalPages : " + this.totalPages);
			$('.pagination', this.container.parent()).remove();
			var pagination = $('<div class="pagination"></div>').append('<a class="nav prev disabled" data-next="false"><</a>');
			console.log("pagination : " + pagination);

			var _Pages = 0;

			for (var i = 0; i < this.totalPages; i++) {
				var pageElClass = "page";
				if (!i)
				    pageElClass = "page current";

				var pageEl = "";
			    //Generate all pages
				if (i <= 4) {
				    pageEl = '<a class="' + pageElClass + '" data-page="' + (
				    i + 1) + '">' + (
				    i + 1) + "</a>";
				}

				else {
                    pageEl = '<a class="' + pageElClass + '" data-page="' + (
				    i + 1) + '" style="display:none;">' + (
				    i + 1) + "</a>";
                    _Pages = 1;
				}
                //

				pagination.append(pageEl);
			}

			if (_Pages == 1)
			    pagination.append('<a class="nav next" data-next="true">></a>');

			this.container.after(pagination);

			var that = this;
			$(".bodys").off("click", ".nav");
			this.navigator = $(".bodys").on("click", ".nav", function() {
				var el = $(this);
				that.navigate(el.data("next"));
			});

			$(".bodys").off("click", ".page");
			this.pageNavigator = $(".bodys").on("click", ".page", function() {
				var el = $(this);
				that.goToPage(el.data("page"));
			});
		},
		navigate: function(next) {
			// default perPage to 5
		    console.log("next : " + next);
			if (isNaN(next) || next === undefined) {
				next = true;
			}
			$(".pagination .nav").removeClass("disabled");
			if (next) {
			    var current_LastPage_id = $(".page:visible:last").data("page");
                
			    var lis = document.getElementsByClassName("page");

			    var count = 0;
			    for (var j = current_LastPage_id; j > 0; j--) {
			        
			        if (count < 5) {
			            //console.log(j);
			            if (lis[j-1] != null)
			                lis[j-1].style["display"] = "none";
			            count++;
			        }
			        else {
			            break;
			        }
			    }

			    for (var i = current_LastPage_id; i < current_LastPage_id + 5; i++) {
			        if (lis[i] != null)
			            lis[i].style["display"] = "inline-block";
			    }

			    this.currentPage = this.currentPage+5;

				if (this.currentPage > (this.totalPages - 1))
					this.currentPage = (this.totalPages - 1);

				if ((current_LastPage_id + 5) >= this.totalPages)
				    $(".pagination .nav.next").addClass("disabled");
				}
			else {
			    var current_FirstPage_id = $(".page:visible:first").data("page");

			    //console.log('first ' + current_FirstPage_id);
			    var lis = document.getElementsByClassName("page");
			    var count = 0;
			    for (var j = current_FirstPage_id; j > 0; j++) {
			        
			        if (count < 5) {
			            //console.log(j);
			            if (lis[j-1] != null)
			                lis[j-1].style["display"] = "none";
			            count++;
			        }
			        else {
			            break;
			        }
			    }

			    var countshow = 0;
			    for (var j = current_FirstPage_id-1; j > 0; j--) {

			        if (countshow < 5) {
			            if (lis[j - 1] != null)
			                lis[j-1].style["display"] = "inline-block";
			            countshow++;
			        }
			        else {
			            break;
			        }
			    }

			    this.currentPage = this.currentPage -5 ;
				if (this.currentPage < 0)
					this.currentPage = 0;
				if (this.currentPage == 0 || current_FirstPage_id == "6")
				    $(".pagination .nav.prev").addClass("disabled");
				}

			this.showItems();
		},
		updateNavigation: function() {
			var pages = $(".pagination .page");
			pages.removeClass("current");
			$('.pagination .page[data-page="' + (
			this.currentPage + 1) + '"]').addClass("current");
		},
		goToPage: function(page) {
		    //console.log(page);
		    this.currentPage = page - 1;
		    //console.log( this.currentPage = page - 1);
		    console.log("currentPage : " + this.currentPage);
		    console.log("totalPage : " + this.totalPages);

		    $(".pagination .nav").removeClass("disabled");

		    var current_LastPage_id = $(".page:visible:last").data("page");

		    if (current_LastPage_id == this.totalPages)
				$(".pagination .nav.next").addClass("disabled");

			var current_FirstPage_id = $(".page:visible:first").data("page");

			if (current_FirstPage_id == "1")
				$(".pagination .nav.prev").addClass("disabled");
			this.showItems();
		},
		showItems: function() {
			this.items.hide();
			var base = this.perPage * this.currentPage;
			this.items.slice(base, base + this.perPage).show();

			this.updateNavigation();
		},
		init: function(container, items, perPage) {
			this.container = container;
			this.currentPage = 0;
			this.totalPages = 1;
			this.perPage = perPage;
			this.items = items;
			this.createNavigation();
			this.showItems();
		}
	};

	// stuff it all into a jQuery method!
	$.fn.pagify = function(perPage, itemSelector) {
		var el = $(this);
		var items = $(itemSelector, el);

		// default perPage to 5
		if (isNaN(perPage) || perPage === undefined) {
			perPage = 3;
		}

		// don't fire if fewer items than perPage
		if (items.length <= perPage) {
			return true;
		}

		pagify.init(el, items, perPage);
	};
})(jQuery);

$(".container").pagify(4, ".divbg");