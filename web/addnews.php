<?
session_start();
include("includes/functions.php");
if(isset($_SESSION['loggedin']))
{
	$er = 0;
	if (!isset($_POST['title'])) $er='1';
		else $title = $_POST['title'];
	if (!isset($_POST['formcategory'])) $er='1';
		else $formcategory = $_POST['formcategory'];
	if (!isset($_POST['body'])) $er='1';
		else $body = $_POST['body'];

	if($er==1)
	{
		header("location: index.php?error=Please input username and password");
	}
	else
	{
		//Insert news
		$title = strip_all($title);
		$formcategory = strip_all($formcategory);
		$body = strip_all($body);
		$username = $_SESSION['username'];
		$query = "INSERT INTO News (`title` ,`body` ,`owner` ,`publishing_date` ,`publishing_time` ,`category`) VALUES ( '".$title."', '".$body."', '".$username."', NOW( ) , NOW( ) , '".$formcategory."' )";
		@mysql_query($query) or die(mysql_query());

		//Read image
		if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
			$tmpName  = $_FILES['image']['tmp_name'];  // Temporary file name stored on the server
			$fp = fopen($tmpName, 'rb');
			$image_data = fread($fp, filesize($tmpName));
			$image_data = addslashes($image_data);
			fclose($fp);

			//Insert image
			$query = "INSERT INTO Images (`news_id` ,`image`) VALUES ( '".@mysql_insert_id()."', '".$image_data."' )";
			@mysql_query($query) or die(mysql_query());
		}
		header("location: index.php?msg=News added to the system.");
	}
}
else header("location: index.php?error=You must be logged in to access this feature.");
?>
