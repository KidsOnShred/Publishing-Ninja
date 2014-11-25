<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/init.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/header.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/classes/Editor.class.php');
	$_SESSION['magazineId'] = $_GET['magazineId'];
	
?>

          <h1 class="page-header">Editor</h1>
			
          <div id="returnedContent"></div>
			<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Crop Image</h4>
                        </div>
                        <div class="modal-body">
                        
                			
                         </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary btn-submit" data-action="SubmitForm">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
      </div>
    </div>
<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/footer.php');
?>
<script type="text/javascript">
	$(document).ready(function() {
		editImages(<?php echo $_GET['pageId']; ?>);
	});
	
</script>
   