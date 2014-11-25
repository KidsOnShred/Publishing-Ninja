<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/init.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/header.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/classes/Publication.class.php');
	$publicationId =  $_GET['publicationId'];
	if ($PubManager->auth($publicationId))
	{
		$Publication = new Publication($core, $publicationId);
		echo '<h2 class="sub-header">'.$Publication->getName().'</h2>
		<div id="returnedContent"></div>';
	}
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

    include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/footer.php');
?>
<script type="text/javascript">
		$(document).ready(function() {
			displayMagazines(<?php echo $publicationId; ?>);
		});
		$(function() {
			 $( ".sections" ).sortable();
		});

</script>