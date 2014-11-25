<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/init.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/header.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/classes/Editor.class.php');
	
	$_SESSION['magazineId'] = $_GET['magazineId'];
	if ($PubManager->auth($_GET['magazineId']))
	{
		
		$Editor = new Editor($core);

		 echo '<h1 class="page-header">Editor</h1>
          <div id="returnedContent"></div>';
	}
?>

         
        </div>
      </div>
    </div>
<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/footer.php');
?>
 <script type="text/javascript">
		$(document).ready(function() {
			displayPages();
		});
		$(function() {
   		 $( ".issues" ).sortable();
  	});

	</script>
   