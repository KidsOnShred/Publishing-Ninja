$(function() {
	$('#returnedContent').on('click', '.btn-delete', deletePage);
	$('.editor-form').on('click', '.btn-section', { el : '.btn-section'} , handleForm);
	$('.editor-form').on('keyup', '.title', { el : '.title'} , handleForm);
	$('.editor-form').on('click', '.btn-layout', { el : '.btn-layout'} , handleForm);
	$('.editor-form').on('click', '.btn-secondary', { el : '.btn-secondary'} , handleForm);
	$('.editor-form').on('change', '.content-file', { el : '.content-file'} , handleForm);
	$('.editor-form').on('click', '#standfirst', { el : '.editor-standfirst'} , handleForm);
	$('.editor-form').on('click', '#content', { el : '.editor-content'} , handleForm);
	$('.editor-form').on('click', '.btn-addsection', showAddSection);

	$('.modal').on('hide.bs.modal', displayImages);

	//$('.editor-submit').on('click', '#submitForm' , function() { $('#editor-form').submit(); });
	$('#returnedContent').on('click', '.btn-moveimg', toggleMovable);
	$('#returnedContent').on('click', '.btn-resizeimg', toggleResizable);
	$('#returnedContent').on('click', '.btn-deleteimg', deleteImg);
	$('#returnedContent').on('click', '.btn-caption', showCaption);
	$('#returnedContent').on('click', '.btn-editcaption', showEditCaption);
	$('#returnedContent').on('click', '.btn-addcaption', addCaption);
	$('#returnedContent').on('click', '.btn-cancelcaption', cancelCaption);
	$('#returnedContent').on('click', '.btn-removecaption', removeCaption);
	$('#returnedContent').on('click', '.btn-removeimg', removeImg);
	$('#returnedContent').on('click', '.btn-saveimg', saveImages);
	$('#returnedContent').on('click', '.btn-action', performAction);
	$('#returnedContent').on('click', '.btn-float', toggleFloat);
	$('#returnedContent').on('click', '.btn-addlink', showAddLink);
	$('#returnedContent').on('click', '.btn-showcrop', showImageCrop);
	$('.row').on('click', '.btn-uploadimage', displayImageUpload);
	$('#modal').on('click', '.btn-submit', submitForm);
	$('#modal').on('click', '.btn-section', showAddSection);
	$('#modal').on('click', '.btn-editsection', showEditSection);
	$('#modal').on('click', '.btn-deletesection', deleteSection);
	$('#modal').on('click', '.btn-cropimg', cropImage);

	$('#returnedContent').on( 'sortstop', '.issues', function( event, ui ) {
	$('.issues').sortable();
		var pages = $('.issues').sortable('toArray');
		$.post(
			"/publisher/methods/editor.php", 
			{action : 'Reorder', pages : pages}, 
			function(data){
				if(data.length > 0){	
					$('#returnedContent').hide();				
					$('#returnedContent').html(data);
					$('#returnedContent').fadeIn(500);
					$('.loading').hide();
					initSortable($('.issues'));
				}
		});	
	
	} );
	
	$('.modal-body').on( 'sortstop', '.sections', function( event, ui ) {
		
	$('.sections').sortable();
		var sections = $('.sections').sortable('toArray');
		var publicationId = $('#publicationId').val();
		console.log($('#publicationId'));

		$.post(
			"/publisher/methods/publications.php", 
			{action : 'ReorderSections', sections : sections, publicationId : publicationId}, 
			function(data){
				if(data.length > 0){	
					$('.modal-body').hide();				
					$('.modal-body').html(data);
					$('.editor-submit').remove();

					$('.modal-body').fadeIn(500);
					$('.loading').hide();
					initSortable($('.sections'));
				}
		});	
	
	} );
	
	
});

$(document).ready(function() {
	$('body').animate({
			scrollTop: 0
	}, 500);
	//$('.editor-layout').hide();	

	//$('.editor-secondary').hide();
	//$('.editor-standfirst').hide();
	//$('.editor-content').hide();
	//$('.editor-submit').hide();
	
});

function resizeImageDropbox() {
	if($('.dropbox-frontcover').length>0) {
		$('.dropbox-frontcover').height(400);
		$('.media-dropbox').attr('data-positionid', 0);
	}
	if($('.dropbox-master').length>0) {
		var ratio = 1000/400;
		var height = $('.dropbox-master').width()/ratio;
		$('.dropbox-master').height(height);
		$('.media-dropbox').attr('data-positionid', 3);
	}
	if($('.dropbox-twothirds').length>0) {
		var ratio = 667/1000;
		var height = $('.dropbox-twothirds').width()/ratio;
		console.log($('.dropbox-twothirds').width());
		$('.dropbox-twothirds').height(height);
		$('.media-dropbox').attr('data-positionid', 4);
	}
	if($('.dropbox-half').length>0) {
		$('.dropbox-half').height('100%');
		$('.media-dropbox').attr('data-positionid', 5);
	}
	if($('.dropbox-fullpage').length>0) {
		$('.dropbox-fullpage').height(600);
		$('.media-dropbox').attr('data-positionid', 7);
	}
}

function handleForm() {
	var sectionFlag = false;
	var titleFlag = false;
	var layoutFlag = false;
	var secondaryFlag = false;
	var fileFlag = false;
	var layoutFrontCover = false;
	var layoutMaster = false;
	var layoutTwoThirds = false;
	var layoutHalfPage = false;
	var layoutFullPage = false;
	
	/*var element = $(this);
	
	if (element == '.btn-section')
		sectionFlag = true;

	$('.btn-section').each(function() {
		if ($(this).hasClass('active') || $('input[name=section]:checked').length > 0) {
			sectionFlag = true;	
		}
	});
	if ($('.title').val().length > 0)
		titleFlag = true;
		
	if (element == '.btn-layout')
		layoutFlag = true;*/
	
	$('.btn-layout').each(function() {
		if ($(this).hasClass('active')) {

			if ($(this).find('input').val() == '1')
				layoutFrontCover = true;
			else if ($(this).find('input').val() == '2')
				layoutContents = true;
			else if ($(this).find('input').val() == '3')
				layoutMaster = true;
			else if ($(this).find('input').val() == '4')
				layoutTwoThirds = true;
			else if ($(this).find('input').val() == '5')
				layoutHalfPage = true;
			else
				layoutFullPage = true;
			
			layoutFlag = true;
		}
	});
	
	if (layoutFrontCover) {
		$('.editor-secondary h4').html('Front Cover Image');
		$('.image-position').val(1);	
	}
	else if (layoutMaster) {
		$('.editor-secondary h4').html('Master Image');	
		$('.image-position').val(3);
	}
	else if (layoutTwoThirds) {
		$('.editor-secondary h4').html('Two Thirds Image');	
		$('.image-position').val(4);
	}
	else if (layoutHalfPage) {
		$('.editor-secondary h4').html('Half Page Image');	
		$('.image-position').val(5);
	}
	else if (layoutFullPage) {
		layoutFlag = false;
		fileFlag = false;
	}
	
	/*if (element == '.btn-secondary')
		secondaryFlag = true;
	
	$('.btn-secondary').each(function() {
		if ($(this).hasClass('active')) {
			secondaryFlag = true;	
		}
	});
	if (element == '.content-file') {
		if ($('.content-file').val().length > 0) {
			if ($('input[name=media]:checked').val() == 'Image') {
				if (isImage($('.content-file').val()))
					fileFlag = true;
			} else {
				if (isHTML($('.content-file').val()))
					fileFlag = true;
			}
		}
	}*/
	
	sectionFlag = true;
	titleFlag = true;
	layoutFlag = true;
	secondaryFlag = true;
	fileFlag = true;
	
	if (sectionFlag)
		$('.editor-title').animate({ opacity: 1 });
	
	if (titleFlag)
		$('.editor-layout').animate({ opacity: 1 });
	
	if (layoutFlag)
		$('.editor-secondary').animate({ opacity: 1 });
	
	if (secondaryFlag) {
		$('.editor-file').slideDown();
	}
		
	if (fileFlag) {
		$('.editor-standfirst').animate({ opacity: 1 });
		$('.editor-content').animate({ opacity: 1 });
		$('.editor-submit').animate({ opacity: 1 });
	}
	
	/*if (element != '.title' && element != '.btn-secondary' && element != '') {
		$('body').animate({
			scrollTop: $(element).offset().top
		}, 1000);
	}*/	
}

function validateForm() {
	var errorFlag = false;
	
	$('.btn-section').each(function() {
		if (!$(this).hasClass('active') && $('input[name=section]:checked').length == 0) {
			errorFlag = true;	
		}
	});
}

function showImageCrop() {
	$(this).addClass('btn-success');
	var div = $(this).parent().parent();
	var mediaId = div.attr('id');
	$.post(
		"/publisher/methods/editor.php", 
		{action : 'DisplayImageCrop', mediaId : mediaId}, 
		function(data){
			if(data.length > 0){	
				$('.modal-body').hide();				
				$('.modal-body').html(data);
				$('.modal-body').fadeIn(500);
				$('.loading').hide();
				$('#modal').modal('show');
				$('.btn-submit').addClass('btn-cropimg');
				$('.btn-cropimg').removeClass('btn-submit');
			}
	});	
}
function cropImage() {
	var mediaId = $('#mediaId').val();
	var data = $('#croppingContainer').find('form').serializeArray();
	$.post(
		"/publisher/methods/editor.php", 
		{action : 'CropImage', mediaId : mediaId, data : data}, 
		function(data){
			if(data.length > 0){	
				$('.modal-body').hide();				
				$('.modal-body').html(data);
				$('.modal-body').fadeIn(500);
				$('.loading').hide();
				$('.btn-cropimg').hide();
				$('.btn-cancel').html('Close');
				$('#modal').modal('show');
				var source = $('#'+mediaId+' img').attr('src');
				var time = new Date().getTime();
				$('#'+mediaId+' img').attr('src', source+'?t='+time);

			}
	});

}

function isImage(filename) {
	var file = filename.split('.');
	filename = file[1].toLowerCase();
	if (filename == 'jpg' || filename == 'jpeg' || filename == 'png' || filename == 'gif')
		return true;
	return false;
}
function isHTML(filename) {
	var file = filename.split('.');
	filename = file[1].toLowerCase();
	if (filename == 'html' || filename == 'htm')
		return true;
	return false;	
}
function deletePage(e) {
	if (confirm('Are you sure you want to delete this page?'))
	{
		var pageId = $(this).data('id');
		$.post(
			"/publisher/methods/editor.php", 
			{action : 'Delete', pageId : pageId}, 
			function(data){
				if(data.length > 0){	
					$('#returnedContent').hide();				
					$('#returnedContent').html(data);
					$('#returnedContent').fadeIn(500);
					$('.loading').hide();
				}
		});	
	}
}
function displayPages() {
	$.post(
		"/publisher/methods/editor.php", 
		{action : 'DisplayPages'}, 
		function(data){
			if(data.length > 0){	
				$('#returnedContent').hide();				
				$('#returnedContent').html(data);
				$('#returnedContent').fadeIn(500);
				$('.loading').hide();
				initSortable($('.issues'));
			}
	});	
}
function displayImages() {
	var pageId = $('#pageId').val();
	$.post(
		"/publisher/methods/editor.php", 
		{action : 'DisplayImages', pageId : pageId}, 
		function(data){
			if(data.length > 0){	
				$('.image-preview-container').hide();				
				$('.image-preview-container').html(data);
				$('.image-preview-container').fadeIn(500);
				$('.loading').hide();
			}
	});	
}
function displayMagazines(publicationId) {
	console.log('this is getting called');
	$.post(
		"/publisher/methods/publications.php", 
		{action : 'DisplayMagazines', publicationId : publicationId }, 
		function(data){
			if(data.length > 0){	
				$('#returnedContent').hide();				
				$('#returnedContent').html(data);
				$('#returnedContent').fadeIn(500);
				$('.loading').hide();
				initSortable($('.sections'));
			}
	});	
}
function displayAddMagazine() {
	$.post(
		"/publisher/methods/publications.php", 
		{action : 'DisplayAddMagazine'}, 
		function(data){
			if(data.length > 0){	
				$('#returnedContent').hide();				
				$('#returnedContent').html(data);
				$('#returnedContent').fadeIn(500);
				$('.loading').hide();
			}
	});	
}

function showAddSection() {
	var publicationId = $('#publicationId').val();
	$.post(
		"/publisher/methods/publications.php", 
		{action : 'DisplayAddSection', publicationId : publicationId}, 
		function(data){
			if(data.length > 0){

				$('#modal .modal-body').hide();				
				$('#modal .modal-body').html(data);
				$('#modal .modal-body').fadeIn(500);
				$('#modal .loading').hide();
				$('#modal .btn-submit').attr('data-action', 'AddSection');
				$('#modal .btn-submit').attr('data-publicationid',  publicationId);
				$('.btn-submit').show();
				$('#modal').modal('show');
			}
	});	
}
function showEditSection() {
	var publicationId = $('#publicationId').val();
	var sectionId = $(this).attr('id');
	$.post(
		"/publisher/methods/publications.php", 
		{action : 'DisplayEditSection', publicationId : publicationId, sectionId : sectionId}, 
		function(data){
			if(data.length > 0){	
				$('.modal-body').hide();				
				$('.modal-body').html(data);
				$('.modal-body').fadeIn(500);
				$('.loading').hide();
				$('.btn-submit').data('action', 'EditSection');
			}
	});	
}
function deleteSection() {
	var publicationId = $('#publicationId').val();
	var sectionId = $(this).attr('id');
	var magazineId = $(this).data('magazineid');
	if (confirm('Are you sure you want to delete this section?')) {
		$.post(
			"/publisher/methods/publications.php", 
			{action : 'DeleteSection', publicationId : publicationId, magazineId : magazineId, sectionId : sectionId}, 
			function(data){
				if(data.length > 0){	
					$('.modal-body').hide();				
					$('.modal-body').html(data);
					$('.modal-body').fadeIn(500);
					$('.loading').hide();
				}
		});	
	}
}
function editImages(pageId) {
	$.post(
		"/publisher/methods/editor.php", 
		{action : 'EditImages', pageId : pageId}, 
		function(data){
			if(data.length > 0){	
				$('#returnedContent').hide();				
				$('#returnedContent').html(data);
				$('#returnedContent').fadeIn(500);
				$('.loading').hide();
				$('.img-default').each(function() {
					if (!$(this).hasClass('img-active')) {
					$(this).append('<div class="img-controls btn-group btn-group-xs"><a class="btn btn-moveimg btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-arrows"></i></a><a class="btn btn-resizeimg btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-expand"></i></a><a class="btn btn-showcrop btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-crop"></i></a><a class="btn btn-caption btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-comment"></i></a><a class="btn btn-float btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-indent"></i></a><a class="btn btn-addlink btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-link"></i></a></div><div class="img-delete btn-group btn-group-xs"><a class="btn btn-deleteimg btn-danger" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-trash-o"></i></a></div>');
					}
					var div = $(this);
					var img = $(this).find('img');
					img.on('load', function(){
						var ratio = $(this).width() / $(this).height();
						makeImgResizable(div, ratio);
						makeImgMovable(div);
						
					});
					
				});
				$( '#carousel' ).elastislide( {
							minItems : 2
				} );
				resizeImageDropbox();
			}
	});	
}
function toggleFloat() {
	var button = $(this);
	var div = button.parent().parent();
	if ($(div).css('float') == 'left' || $(div).css('float') == 'right') 
		$(div).css('float', 'none');
	else
		$(div).css('float', 'left');
}
function showAddLink() {
	$(this).addClass('btn-success');
	var div = $(this).parent().parent();
	var mediaId = div.attr('id');
	$.post(
		"/publisher/methods/editor.php", 
		{action : 'DisplayAddLink', mediaId : mediaId}, 
		function(data){
			if(data.length > 0){	
				$('.modal-body').hide();				
				$('.modal-body').html(data);
				$('.modal-body').fadeIn(500);
				$('.loading').hide();
				$('#modal').modal('show');
				$('.btn-submit').attr('data-action', 'AddLink');
				$('.btn-submit').attr('data-currentpage', 'editor');
				$('.btn-submit').show();
			}
	});	
}
function addImageControls() {
	$('.img-default').each(function() {
		if (!$(this).hasClass('img-active')) {
		$(this).append('<div class="img-controls btn-group btn-group-xs"><a class="btn btn-moveimg btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-arrows"></i></a><a class="btn btn-resizeimg btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-expand"></i></a><a class="btn btn-showcrop btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-crop"></i></a><a class="btn btn-caption btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-comment"></i></a><a class="btn btn-float btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-indent"></i></a><a class="btn btn-addlink btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-link"></i></a></div><div class="img-delete btn-group btn-group-xs"><a class="btn btn-deleteimg btn-danger" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-trash-o"></i></a></div>');
		}
		var div = $(this);
		var img = $(this).find('img');
		img.on('load', function(){
			var ratio = $(this).width() / $(this).height();
			makeImgResizable(div, ratio);
			makeImgMovable(div);
			
		});
		
	});
}
function toggleResizable(event) {
	
	var button = $(this);
	var image = button.parent().parent();
	image.draggable('disable');
	image.find('.btn-moveimg').removeClass('btn-active');
	image.find('.btn-moveimg').removeClass('btn-success');
	
	if (button.hasClass('btn-active')) {
		button.removeClass('btn-active');
		button.removeClass('btn-success');
		image.resizable('disable');
	} else {
		button.addClass('btn-active');
		button.addClass('btn-success');
		image.resizable('enable');
		if (image.css("float") == 'right')
			image.find('.ui-icon-gripsmall-diagonal-se').addClass('ui-icon-gripsmall-diagonal-sw');
		else
			image.find('.ui-icon-gripsmall-diagonal-se').removeClass('ui-icon-gripsmall-diagonal-sw');
	}
}
function toggleMovable(event) {
	
	var button = $(this);
	var image = button.parent().parent();
	image.resizable('disable');
	image.find('.btn-resizeimg').removeClass('btn-active');
	image.find('.btn-resizeimg').removeClass('btn-success');
	
	if (button.hasClass('btn-active')) {
		button.removeClass('btn-active');
		button.removeClass('btn-success');
		image.draggable('disable');
	} else {
		button.addClass('btn-active');
		button.addClass('btn-success');
		image.draggable('enable');	
	}
}
function deleteImg() {
	var div = $(this).parent().parent();
	var mediaId = div.attr('id');
	if(confirm("Are you sure you want to delete this image?")) {
		$.post(
			"/publisher/methods/editor.php", 
			{action : 'DectivateMedia', mediaId : mediaId}, 
			function(data){
				if(data.length > 0){
					div.remove();
				}
		});
	}
}
function removeImg() {
	var div = $(this).parent().parent();
	var container = div.parent();
	var mediaId = div.attr('id');

	
	$.post(
		"/publisher/methods/editor.php", 
		{action : 'DeactivateMedia', mediaId : mediaId}, 
		function(data){
			if(data.length > 0){
				$('#carousel li:last-child').clone().appendTo('#carousel').html(div);
				$('#carousel li:last-child').find('.img-default').css('width', '200px');
				div.find('.img-controls').remove();
				div.find('.img-delete').remove();
				div.append('<div class="img-controls btn-group btn-group-xs"><a class="btn btn-moveimg btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-arrows"></i></a><a class="btn btn-resizeimg btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-expand"></i></a><a class="btn btn-showcrop btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-crop"></i></a><a class="btn btn-caption btn-primary" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-comment"></i></a></div><div class="img-delete btn-group btn-group-xs"><a class="btn btn-deleteimg btn-danger" data-img="'+$(this).attr('id')+'" role="button"><i class="fa fa-trash-o"></i></a></div>');
				container.html(data);
				resizeImageDropbox();
			}
	});
	//$( '#carousel' ).elastislide().destroy();
}
function makeImgResizable(div, ratio) {
	
	div.resizable({
		aspectRatio: true,
		create: function() {
			//div.css('width', div.height()+'px');	
		},
		resize: function() {
			div.css("position", "relative");
			div.css("top", "auto");
			div.css("left","auto");	
			div.css('height', 'auto');	
		},
		stop: function() {
			div.css("position", "relative");
			//div.css('height', 'auto');	
		}
	});
	div.resizable('disable');
}
function makeImgMovable(div) {
	$( ".media-dropbox" ).droppable({
		activeClass: "img-drag-hover",
		hoverClass: "img-drag-active",
		drop: function( event, ui ) {
			var div = $(ui.draggable);
			var id = div.attr('id');
			var number = id.split('img-');
			var li = $('#li-'+number[1]);
			var positionId = $('.media-dropbox').attr('data-positionid');
			
			$.post(
				"/publisher/methods/editor.php", 
				{action : 'SetMediaPosition', mediaId : id, positionId : positionId}, 
				function(data){
					if(data.length > 0){	
						//li.remove();
						div.find('.img-delete').html('<a class="btn btn-removeimg btn-danger" role="button"><i class="fa fa-level-up"></i></a>');
						div.find('.btn-moveimg').hide();
						div.find('.btn-resizeimg').hide();
						div.find('.btn-caption').hide();
						//$(this).removeClass('img-drag-active');
						div.draggable("disable");
					}
			});
		},
		deactivate : function ( event, ui ) {
			
		},
		over: function( event, ui ) {
			var div = $(ui.draggable);
			div.width($(this).width());
			$(this).html(div);
		}
	});
	$( "#droppable p" ).droppable({
		activeClass: "img-drag-hover",
		hoverClass: "img-drag-active",
		drop: function( event, ui ) {
			$(this).animate({height: 'auto'}, 1000);
			var div = ui.draggable;
			var left = div.css('left');
			left = left.split('px');
			if (left[0] > ($('#returnedContent').width()/2))
				var float = 'right';
			else
				var float = 'left';
			div.css("top", "auto");
			div.css("left","auto");	
			div.css("float", float);
			var mediaId = div.attr('id');

			/*$.post(
				"/publisher/methods/editor.php", 
				{action : 'ActivateMedia', mediaId : mediaId}, 
				function(data){
					if(data.length > 0){	
						$('#returnedContent').hide();				
						$('#returnedContent').html(data);
						$('#returnedContent').fadeIn(500);
						$('.loading').hide();
					}
			});*/	
			div.find('.img-delete').html('<a class="btn btn-removeimg btn-danger" role="button"><i class="fa fa-level-up"></i></a>');
			var number = mediaId.split('img-');
			var li = $('#li-'+number[1]);
			li.remove();
		},
		deactivate : function ( event, ui ) {
			var div = $(ui.draggable);
			var left = ui.helper.css('left');
			left = left.split('px');
			if (left[0] > ($('#returnedContent').width()/2))
				var float = 'right';
			else
				var float = 'left';
			
			div.css("float", float);
			ui.helper.fadeOut();
			$(this).removeClass('img-drag-active');
		},
		over: function( event, ui ) {
			var div = $(ui.draggable);
			div.insertBefore($(this));
		}
	});
	div.draggable({
		//revert: true,
		cursor: 'move',
		cursorAt: { top: 10, left: 10 },
		snap: true,
		revert: true,
		helper: 'clone',
		start: function (event, ui) {
			div.css('opacity', '0.5');
			var helper = $(ui.helper);
			helper.width(div.width()/4);
			helper.height(div.height()/4);
			helper.addClass('.ui-helper');
			helper.find('.btn-group').hide();
		},
		drag: function (event, ui) {
			var left = ui.helper.css('left');
			left = left.split('px');
			if (left[0] > ($('#returnedContent').width()/2))
				var float = 'right';
			else
				var float = 'left';
			div.css("float", float);
			
		},
		stop: function() {
			div.css('opacity', '1');
		}
		
	});
	div.draggable("disable");
}
function showCaption() {
	var button = $(this);
	var imageDiv = button.parent().parent();
	var captionDiv = $('#caption-edit');
	var captionHTML = '<textarea></textarea><a class="btn btn-addcaption btn-primary">Add Caption</a><a class="btn btn-cancelcaption btn-default">Cancel</a>';

	if (!button.hasClass('btn-active')) {
		button.addClass('btn-active');
		button.addClass('btn-success');
		
		if (captionDiv.length == 0) {
			imageDiv.append('<div class="img-caption" id="#caption-edit"></div>');
			captionDiv = imageDiv.find('.img-caption');	
		}
		
		//var caption = captionDiv.val();
		captionDiv.html(captionHTML);
		captionDiv.find('textarea').val(caption);
		
		imageDiv.height('auto');
		$('.img-caption textarea').focus();
	}
}
function showEditCaption() {
	var button = $(this);
	var captionDiv = button.parent().parent();
	captionDiv.find('.caption-edit').remove();
	var caption = captionDiv.html();
	var captionHTML = '<textarea></textarea><a class="btn btn-addcaption btn-primary">Add Caption</a><a class="btn btn-cancelcaption btn-default">Cancel</a>';
	captionDiv.html(captionHTML);
	captionDiv.find('textarea').val(caption);
	
}
function addCaption() {
	var button = $(this).parent().parent().find('.btn-caption');
	var captionDiv = $(this).parent();
	var caption = captionDiv.find('textarea').val()+'<div class="caption-edit btn-group"><a class="btn btn-removecaption btn-danger" role="button"><i class="fa fa-trash-o"></i></a><a class="btn btn-primary btn-editcaption"><i class="fa fa-edit"></i></a></div>';
	//button.removeClass('btn-active');
	//button.removeClass('btn-success');
	//button.addClass('btn-editcaption');
	//button.removeClass('btn-caption');
	captionDiv.html(caption);
}
function cancelCaption() {
	var button = $(this).parent().parent().find('.btn-caption');
	button.removeClass('btn-active');
	button.removeClass('btn-success');
	$(this).parent().remove();
}
function removeCaption() {
	var button = $(this).parent().parent().parent().find('.btn-caption');
	button.removeClass('btn-active');
	button.removeClass('btn-success');
	$(this).parent().parent().remove();
}
function removeEditingTools() {
	$('.img-default').resizable( "destroy" );
	$('.img-controls').remove();
	$('.img-delete').remove();
	$('.caption-edit').remove();
	$('textarea').remove();
	$('.btn-addcaption').remove();
	$('.btn-cancelcaption').remove();
}
function saveImages() {
	removeEditingTools();
	var pageId = $(this).data('pageid');
	var content = $('.preview-content').html();
	$.post(
		"/publisher/methods/editor.php", 
		{action : 'SaveImages', pageId : pageId, content : content}, 
		function(data){
			if(data.length > 0){	
				$('#returnedContent').hide();				
				$('#returnedContent').html(data);
				$('#returnedContent').fadeIn(500);
				$('.loading').hide();
			}
	});	
}
function displaySections() {
	$.post(
		"/publisher/methods/publications.php", 
		{action : 'DisplaySections'}, 
		function(data){
			if(data.length > 0){	
				$('#returnedContent').hide();				
				$('#returnedContent').html(data);
				$('#returnedContent').fadeIn(500);
				$('.loading').hide();
				initSortable($('.issues'));
			}
	});	
}
function performAction() {
	var action = $(this).data('action');
	var publicationId =  $(this).data('publicationid');
	var magazineId =  $(this).data('magazineid');
	var pageId =  $(this).data('pageid');
	var title = $(this).data('title');
	$('.btn-submit').show();
	$('.btn-cancel').html('Cancel');
	
	if (typeof(title) == 'undefined')
		title = 'Publisher';
	$.post(
		"/publisher/methods/publications.php", 
		{action : action, publicationId : publicationId, magazineId : magazineId, pageId : pageId}, 
		function(data){
			if(data.length > 0){	
				$('.modal-body').html(data);
				$('.modal-title').html(title);
				$('.editor-submit').remove();
				$('.btn-submit').attr('data-publicationid', publicationId);
				$('.btn-submit').attr('data-magazineid', magazineId);
				$('.btn-submit').attr('data-pageid', pageId);
				var action = $('#action').val();
				$('.btn-submit').attr('data-action', action);
				
				$('#modal').modal('show');
			}
	});	
}
function displayImageUpload() {
	
	$('#uploadimage').modal('show');
	
}
function submitForm() {
	var action = $(this).data('action');
	var publicationId =  $(this).data('publicationid');
	var magazineId =  $(this).data('magazineid');
	var pageId =  $(this).data('pageid');
	var title = $(this).data('title');
	var currentPage = $(this).data('currentpage');
	if (currentPage == 'editor')
		var method = '/publisher/methods/editor.php';
	else
		var method = '/publisher/methods/publications.php';

	var data = $(this).parent().parent().find('form').serializeArray();
	
	$.post(method, {action : action, publicationId : publicationId, magazineId : magazineId, pageId : pageId, data : data }, 
		function(data){
			if(data.length > 0){	
				$('.modal-body').html(data);
				$('.modal-title').html(title);
				$('.editor-submit').remove();
				$('.btn-submit').hide();
				$('.btn-cancel').html('Close');
				//displayMagazines(publicationId);
			}
	});	
}
function ajaxPost(filename, action, returnLocation, data) {
	var variables = '{action : '+action; 
	if (typeof(data) != undefined) {
		data.forEach(function (value) {
			variables += ', '+value+' : '+value;
		});
		variables += '}';
	}
	$.post(
		filename, 
		variables, 
		function(data){
			if(data.length > 0){	
				$(returnLocation).hide();				
				$(returnLocation).html(data);
				$(returnLocation).fadeIn(500);
				initSortable($('.issues'));
			}
	});		
}
function initSortable(c){
   var width = $('.issues .col-sm-2').width()+30;
   console.log(width)
   $(c).sortable({ scroll: 'y', grid: [width, 270], placeholder: 'issues-placeholder', helper: 'clone' });
}
function initDraggable(c) {
	$(c).draggable();
	$(c).css("position", "relative");	
}

function validateForm() {
	var error = false;
	var sectionFlag = false;
	var layoutFlag = false;
	$('.btn-section').each(function() {
		if ($(this).hasClass('active') || $('input[name=section]:checked').length > 0) {
			sectionFlag = true;
		}
		if (!sectionFlag)
			error = true;
	});
	if ($('.title').val().length == 0) {
		error = true;
	}
	
	$('.btn-layout').each(function() {
		if ($(this).hasClass('active')|| $('input[name=section]:checked').length > 0) {
			layoutFlag = true;
		}
		if (!layoutFlag) {
			error = true;
		}
	});

	var content=nicEditors.findEditor('content').getContent();
	if(content.length == 0){  error=true;  }
	if (error == true) {
		alert('Temporary validation\n\nYou need to fill out the whole form or the system will break.');
		return false;
	}
	return true;
	//$('#editor-form').submit();	
}
 