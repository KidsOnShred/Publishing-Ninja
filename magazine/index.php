<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/dbConnect.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/classes/Magazine.class.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/classes/Publication.class.php');
	
	$magazineId = $_GET['magazineId'];
	$Magazine = new Magazine($core, $magazineId);
	
	$publicationId = $Magazine->getPublicationId();
	$Pub = new Publication($core, $publicationId);

?>
<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title><?php echo $Pub->getName(); ?> Magazine</title>
		<meta name="description" content="Bookblock: A Content Flip Plugin - Demo 4" />
		<meta name="keywords" content="javascript, jquery, plugin, css3, flip, page, 3d, booklet, book, perspective" />
		<meta name="author" content="Codrops" />
		<link rel="shortcut icon" href="../favicon.ico"> 
		<link rel="stylesheet" type="text/css" href="/publisher/magazine/css/default.css" />
		<link rel="stylesheet" type="text/css" href="/publisher/magazine/css/bookblock.css" />
		<link rel="stylesheet" type="text/css" href="/publisher/magazine/css/focuspoint.css" />
		<!-- custom demo style -->
		<link rel="stylesheet" type="text/css" href="/publisher/magazine/css/style.css" />
        <link rel="stylesheet" type="text/css" href="/publisher/magazine/css/layouts.css" />
        <link rel="stylesheet" type="text/css" href="/publisher/magazine/css/sections.css" />
        <link rel="stylesheet" type="text/css" href="/publisher/magazine/css/publications/<?php echo $Pub->getId(); ?>.css" />
        
		<script src="/publisher/magazine/js/modernizr.custom.js"></script>
	</head>
	<body>
    	<div class="side-screen"></div>
    	<nav class="menu-bar">
        	<ul>
            	<li class="menu-open"><span class="icon-menu"><span class="menu-opentext">Open Menu</span></span></li>
                <li class="menu-currentpage"><h1>&nbsp;</h1></li>
                <li class="menu-url hide-mobile"><?php echo $Pub->getWebsiteLink(); ?></li>
            </ul>
        </nav>
        <nav class="menu-side">
        	<?php 
				echo $Magazine->createMenu();
			?>
        </nav>
		<?php
			echo $Magazine->createPages();
		?>
        <!--<div class="information">
        	<div class="smartphones">Smartphones</div>
            <div class="smartphones-landscape">Smartphones Landscape</div>
            <div class="smartphones-portrait">Smartphones Portrait</div>
            <div class="ipad">iPad</div>
            <div class="ipad-landscape">iPad Landscape</div>
            <div class="ipad-portrait">iPad Portrait</div>
            <div class="desktop">Desktop</div>
            <div class="desktop-large">Desktop Large</div>
            <div class="iphone4">iPhone 4</div>
        </div>-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="/publisher/magazine/js/jquerypp.custom.js"></script>
		<script src="/publisher/magazine/js/jquery.bookblock.js"></script>
		<script src="/publisher/magazine/js/jquery.focuspoint.js"></script>
		<script>
			var isMobile = {
				Android: function() {
					return navigator.userAgent.match(/Android/i);
				},
				BlackBerry: function() {
					return navigator.userAgent.match(/BlackBerry/i);
				},
				iOS: function() {
					return navigator.userAgent.match(/iPhone|iPad|iPod/i);
				},
				Opera: function() {
					return navigator.userAgent.match(/Opera Mini/i);
				},
				Windows: function() {
					return navigator.userAgent.match(/IEMobile/i);
				},
				any: function() {
					return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
				}
			};
			var Page = (function() {
				
				var config = {
						$bookBlock : $( '#bb-bookblock' ),
						$navNext : $( '#bb-nav-next' ),
						$navPrev : $( '#bb-nav-prev' ),
						$navFirst : $( '#bb-nav-first' ),
						$navLast : $( '#bb-nav-last' ),
						$navContents : $('.bb-nav-jumpto')
					},
					init = function() {
						config.$bookBlock.bookblock( {
							speed : 800,
							shadowSides : 0.8,
							shadowFlip : 0.4,
							magazineId : <?php echo $_GET['magazineId']; ?>
						} );
						initEvents();
					},
					initEvents = function() {
						$(document).ready(function() {

						});
						$(window).resize(function() {
							var maxHeight = 0;
							$('.ad-right').find('img').each(function () {
								if ($(this).height() > maxHeight)
									maxHeight = $(this).height();
							});
							console.log('ad:'+maxHeight);
							console.log('frame:'+$( window ).height());
							if(maxHeight > $( window ).height()) {
								console.log('ad too big - resizing');
								$('.ad-right img').css('width', 'auto');
								$('.ad-right img').css('height', '92%');
							}
							else {
								console.log('ad is fine');
								$('.ad-right img').css('height', 'auto');
								$('.ad-right img').css('width', '100%');
							}
						});
						var $slides = config.$bookBlock.children();

						// add navigation events
						config.$navNext.on( 'click touchstart', function() {
							config.$bookBlock.bookblock( 'next' );
							return false;
						} );

						config.$navPrev.on( 'click touchstart', function() {
							config.$bookBlock.bookblock( 'prev' );
							return false;
						} );
						
						$('.container').on('click', '.bb-nav-jumpto', function() {
							config.$bookBlock.bookblock( 'jump', $(this).data('item') );
							return false;
						});
						
						config.$navContents.on( 'click', function() {
							config.$bookBlock.bookblock( 'jump', $(this).data('item') );
							return false;
						} );

						$('.container').on('click', '.bellyband-container', function() {
							$('.bellyband').addClass('animate-bellyband');
							$('.bellyband-right').addClass('animate-bellyband');
							$('.bellyband').addClass('rotated');
							setTimeout(function() {
								$('.bellyband-right').addClass('rotated-right');
							}, 500);
							setTimeout(function() {
								$('.bellyband-container').hide();
								//config.$bookBlock.bookblock( 'jump', '2' );
							}, 1000);
						});

						//$('#bb-nav-prev').hide();
						
						if(isMobile.any()){
							// add swipe events
							$slides.on( {
								'swipeleft' : function( event ) {
									config.$bookBlock.bookblock( 'next' );
									return false;
								},
								'swiperight' : function( event ) {
									config.$bookBlock.bookblock( 'prev' );
									return false;
								}
							} );
						}

						// add keyboard events
						$( document ).keydown( function(e) {
							var keyCode = e.keyCode || e.which,
								arrow = {
									left : 37,
									up : 38,
									right : 39,
									down : 40
								};

							switch (keyCode) {
								case arrow.left:
									config.$bookBlock.bookblock( 'prev' );
									break;
								case arrow.right:
									config.$bookBlock.bookblock( 'next' );
									break;
							}
						} );
					};

					return { init : init };

			})();
		</script>
        <script src="/publisher/magazine/js/core.js"></script>
	</body>
</html>