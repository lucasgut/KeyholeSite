<?php
	//Avoid having blank spaces before or after the script as they will be returned as part of the image stream
	include("includes/functions.php");
    $id = $_GET['id'];
	if(isset($id) && is_numeric($id)) {
		//Load images
		$imageQuery = "SELECT * FROM Images WHERE id = ".$id.";";
		@mysql_select_db("binary_data");
		$imageRows = mysql_query($imageQuery) or die(mysql_error());
		if(mysql_num_rows($imageRows))
		{
			while($imageRow = mysql_fetch_row($imageRows))
			{ 
				$content = $imageRow[2];
				//echo "Length: ".strlen($content);
				header('Content-type: image/jpg');
				echo $content;
			}
		}
	}
	else {
		echo 'Unable to load image with id'.$id;
	}
?>
