jQuery(document).ready(function($){
	
	//$('body').css({width: 600+'px', overflowY: 'hidden', margin: 0, padding: 0});
	
	/*$('a').on('click', function(){
		return false
	});*/
	
	if (isCanvasSupported()) {
		console.log('attempting preview');
		/*var $div = $('<div />').css({
			position: 'absolute',
			zIndex: 1000,
			width: 100+'%',
			top: 0,
			left: 0,
			height: 20+'px',
			background: '#A82C2A',
			color: '#ffffff',
			textAlign:'center',
			fontWeight: 'bold'
		}).text($('meta[name=wait-message]').attr('content'));*/
		
		//$('body').before($div);
		if ($('img').length > 0)
			$('img').on('load', createPreview);	
		else
			createPreview();
	}
	
});

function createPreview() {
	  html2canvas(document.body, {
		  onrendered: function(canvas) {
			  var img = canvas.toDataURL("image/png");
			  var pageId = $('#pageId').val();
			  var magazineId = $('#magazineId').val();
			  $.post(
				  "/publisher/magazine/methods/preview.php", 
				  {img : img, pageId : pageId, magazineId : magazineId}, 
				  function(data){
					  if(data.length > 0){
						  //$div.remove();
						  console.log(data);
					  }
			  });
		  }
	  });		
}

function isCanvasSupported(){
	var elem = document.createElement('canvas');
	return !!(elem.getContext && elem.getContext('2d'));
}