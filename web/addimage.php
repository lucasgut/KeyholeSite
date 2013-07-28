<?
session_start();
include("includes/functions.php");
if(isset($_SESSION['loggedin']))
{
	//Read image
	$news_id = $_POST['news_id'];
	if (isset($news_id) && isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
		$tmpName  = $_FILES['image']['tmp_name'];  // Temporary file name stored on the server
		$fp = fopen($tmpName, 'r');
		$image_data = fread($fp, filesize($tmpName));
		$image_data = addslashes($image_data);
		fclose($fp);

		//Insert image
		$query = "INSERT INTO Images (`news_id` ,`image`) VALUES ( '".$news_id."', '".$image_data."' )";
		@mysql_query($query) or die(mysql_query());
	}
	else {
		echo "Failed to upload image for news ".$news_id;
	}
	header("location: index.php?msg=Image added to the system.");
}
else header("location: index.php?error=You must be logged in to access this feature.");
?>
