(function ($) {
	"use strict";
	$(window).on('scroll', function () {
		var scroll = $(window).scrollTop();
		if (scroll < 400) {
			$("#sticky-header").removeClass("sticky");
			$('#back-top').fadeIn(500);
		} else {
			$("#sticky-header").addClass("sticky");
			$('#back-top').fadeIn(500);
		}
	});

	$(document).ready(function () {

		if ($(".contentScrollEvent").length) {
			$("html").animate({
				scrollTop: $(".contentScrollEvent").offset().top + $(window).scrollTop() - 100
			}, 600);
		}

		var menu = $('ul#navigation');
		if (menu.length) {
			menu.slicknav({
				prependTo: ".mobile_menu",
				closedSymbol: '+',
				openedSymbol: '-'
			});
		};


		$('.counter').counterUp({
			delay: 10,
			time: 1000
		});

		$.scrollIt({
			upKey: 38,
			downKey: 40,
			easing: 'linear',
			scrollTime: 600,
			activeClass: 'active',
			onPageChange: null,
			topOffset: 0
		});

		$.scrollUp({
			scrollName: 'scrollUp',
			topDistance: '4500',
			topSpeed: 300,
			animation: 'fade',
			animationInSpeed: 200,
			animationOutSpeed: 200,
			scrollText: '<i class="fa fa-angle-double-up"></i>',
			activeOverlay: false,
		});

		$('#searchForm').submit(function (e) {
			e.preventDefault();
			if ($('#searchText').val()) {
				window.location.replace('./search.php?text=' + $('#searchText').val() + '&page=1');
			} else {
				alert("Enter text to search.");
				return;
			}
		});
		$('#searchFormSide').submit(function (e) {
			e.preventDefault();
			if ($('#searchTextSide').val()) {
				window.location.replace('./search.php?text=' + $('#searchTextSide').val() + '&page=1');
			} else {
				alert("Enter text to search.");
				return;
			}
		});

	});

})(jQuery);