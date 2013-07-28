<?
session_start();
include("includes/functions.php");
if(isset($_SESSION['loggedin']))
{
	$er = 0;
	if (!isset($_POST['edittitle'])) $er='1';
		else $title = $_POST['edittitle'];
	if (!isset($_POST['editformcategory'])) $er='1';
		else $formcategory = $_POST['editformcategory'];
	if (!isset($_POST['editbody'])) $er='1';
		else $body = $_POST['editbody'];
	if (!isset($_POST['editid'])) $er='1';
		else $id = $_POST['editid'];
	if($er==1)
	{
		header("location: index.php?error=Please input username and password");
	}
	else
	{
		$title = strip_all($title);
		$formcategory = strip_all($formcategory);
		$body = strip_all($body);
		$username = $_SESSION['username'];
		$query = "UPDATE News SET `body` = '$body', `title` = '$title', `category` = '$formcategory' WHERE `id`='$id';";
		echo $query;
		@mysql_query($query) or die(mysql_query());
		header("location: index.php?msg=News edited successfully");
	}
}
else header("location: index.php?error=You must be logged in to access this feature.");
?>
