<?
session_start();
include("includes/functions.php");
if(isset($_SESSION['loggedin']))
{
	$er = 0;
	if (!isset($_REQUEST['id'])) $er='1';
		else $id = $_REQUEST['id'];
	if($er==1)
	{
		header("location: index.php?error=Please input username and password");
	}
	else
	{
		if(is_numeric($id))
		{
			@mysql_query("DELETE FROM News WHERE id='$id';") or die(mysql_query());
			@mysql_query("DELETE FROM Images WHERE news_id='$id';") or die(mysql_query());
			header("location: index.php?msg=News deleted from the system.");	
		}
		else
		{
			header("location: index.php?error=Hacking attempt.");
		}
	}
}
else header("location: index.php?error=You must be logged in to access this feature.");
?>
