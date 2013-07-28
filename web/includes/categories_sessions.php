<?
	session_start(); 
	$err = 0;
	if(isset($_REQUEST['function'])) $function = $_REQUEST['function'];
	else $err = 1;
	if(isset($_REQUEST['category'])) $category = $_REQUEST['category'];
	else $err = 1;
	
	if(!$err)
	{ 
		if($function == 'add')
		{ 
			if(isset($_SESSION['categories']))
			{ 
				$categories = $_SESSION['categories'];
				$categories[$category] = 1;
				$_SESSION['categories'] = $categories;
			}
			else
			{ 
				// no previous call
				$categories = array();
				$categories[$category] = 1;
				$_SESSION['categories'] = $categories;
			}
			
		}
		elseif($function  == 'remove')
		{
			$categories = $_SESSION['categories'];
			unset($categories[$category]);
			$_SESSION['categories'] = $categories;
		}
	}
	
?>
