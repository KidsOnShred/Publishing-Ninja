<?php	
	class Test
	{
	    static public function getNew()
	    {
	        echo 'test';
	        
	    }
	}
	class MessageManager {
		
		static public function displayMessage($message, $type = '0')
		{
			if ($type == 9)
			{
				echo '<div class="alert alert-info">';
					echo '<pre>';
					print_r($message);
					echo '</pre>';
				echo '</div>';
			}
			if ($type == 8)
			{
				echo '<div class="alert alert-info">';
					echo '<pre>';
					var_dump($message);
					echo '</pre>';
				echo '</div>';
			}
			else if ($type == 1)
			{
				echo '<div class="alert alert-info">';
				echo $message;
				echo '</div>';
			}
			else if ($type == 2)
			{
				echo '<div class="alert alert-danger">';
				echo 'Error: '.$message;
				echo '</div>';
			}
			else
			{
				echo '<div class="alert alert-success">';
				echo $message;
				echo '</div>';
			}
		}
	}
?>