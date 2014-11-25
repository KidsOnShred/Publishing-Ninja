$(function() {
	Page.init();
	
	$('.menu-open').on('click', toggleSideMenu);
	$('.side-screen').on('mouseenter', showSideMenu);
	$('.menu-side').on('mouseleave', hideSideMenu);
	
	$('.submenu').on('click', function() {
		$(this).find('ul').slideToggle();
		var arrow = $(this).find('.arrow-sprite');
		if (arrow.hasClass('arrow-down')){
			arrow.removeClass('arrow-down');
			arrow.addClass('arrow-forward');
		} else {
			arrow.removeClass('arrow-forward');
			arrow.addClass('arrow-down');	
		}
	});
	
	var advertRight = $('.ad-right');

	var offset = 40;
	
	window.onscroll = function()
	{
		if( window.XMLHttpRequest ) {
			//if (document.documentElement.scrollTop > 40 || self.pageYOffset > 40)
			advertRight.animate({ top: (self.pageYOffset+offset)+"px" },1);
		}
	}
});
$(document).ready(function() {
});
function toggleSideMenu() {
	if ($('.menu-side').hasClass('menu-active')) {
		hideSideMenu();
	} else {
		showSideMenu();
	}
}
function showSideMenu() {
	$('.container').stop().animate({width:'70%'},300);
	$('.img-frontcover').stop().animate({width:'130%'},300);
	$('.menu-opentext').html('Close Menu');
	$('.icon-menu').addClass('icon-menu-active');
	$('.menu-side').addClass('menu-active');
	$('.arrow-prev').addClass('prev-active');	
	$('.arrow-next').addClass('next-active');	
	$('.container').addClass('menu-active-container');			
}
function hideSideMenu() {
	$('.container').stop().animate({width:'100%'},300);
	$('.img-frontcover').stop().animate({width:'100%'},300);
	$('.menu-opentext').html('Open Menu');
	$('.container').removeClass('menu-active-container');
	$('.menu-side').removeClass('menu-active');
	$('.arrow-prev').removeClass('prev-active');
	$('.arrow-next').removeClass('next-active');
	$('.icon-menu').removeClass('icon-menu-active');		
}