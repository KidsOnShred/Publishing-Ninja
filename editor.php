<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/init.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/header.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/classes/Editor.class.php');
	
	$Editor = new Editor($core);
	
	echo '<h1 class="page-header">Editor</h1>';
	echo '<div id="returnedContent"></div>
	<div class="modal fade" id="uploadimage" tabindex="-1" role="dialog" aria-labelledby="uploadimage-label" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
    		<div class="modal-content">
	  			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        			<h4 class="modal-title">Upload Images</h4>
      			</div>
      			<div class="modal-body">
					<p class="help-text">Please upload the images for this page. You can edit the size and position of the images in the next screen.</p>
					<form action="http://'.$_SERVER['SERVER_NAME'].'/publisher/methods/editor.php" class="dropzone">
						<input type="hidden" id="action" name="action" value="UploadImages" />
						<input type="hidden" id="pageId" name="pageId" value="'.@$_GET['pageId'].'" />
						<input type="hidden" id="magazineId" name="magazineId" value="'.@$_GET['magazineId'].'" />
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Finish</button>
				</div>
    		</div>
  		</div>
	</div>';
	echo '<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
	  <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
	  	
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary btn-submit" data-action="SubmitForm">Save changes</button>
      </div>
    </div>
  </div>
</div>';
	$Editor->init();
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/footer.php');
	echo '<script type="text/javascript">
		$(document).ready(function() {
			handleForm();
		});
		
	</script>';
?>
	 