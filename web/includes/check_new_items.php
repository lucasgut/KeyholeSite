<?
	session_start();
	include("functions.php");
	$oldLastID = $_SESSION['lastID'];
	$categories_query = "";
	if(isset($_SESSION['categories']))
		{ // we have some registered categories, so our query will not select all news
			if(sizeof($_SESSION['categories'])>0)
			{
				$categorieskeys = array_keys($_SESSION['categories']);
				$count = 0;
				if(sizeof($categorieskeys)>0)
				{
					$categories_query = "AND (";
					while($categorieskeys[$count]!=null)
					{
						if($count>0) $categories_query = $categories_query." OR category = '".$categorieskeys[$count]."' ";
						else $categories_query = $categories_query." category = '".$categorieskeys[$count]."' ";
						$count++;
					}
					$categories_query = $categories_query . " ) ";				
				}	
			}
		}
	
	$start = $_SESSION['start'];
	$end = $_SESSION['end'];	
	
	if($categories_query!='')
	{
		if((isset($_SESSION['start']))&&(isset($_SESSION['end']))&&(strlen($start)!=0)&&(strlen($end)!=0))
		{
			$query = "SELECT * FROM News WHERE id>0 ".$categories_query." AND  publishing_date >= '$start' AND publishing_date <='$end' ORDER BY publishing_date DESC, publishing_time DESC";
		}
		elseif (isset($_SESSION['start'])&&(strlen($start)!=0))
		{
			$query = "SELECT * FROM News WHERE id>0 ".$categories_query." AND publishing_date >= '$start' ORDER BY publishing_date DESC, publishing_time DESC";
		}
		elseif (isset($_SESSION['end'])&&(strlen($end)!=0))
		{
			$query = "SELECT * FROM News WHERE id>0 ".$categories_query." AND publishing_date <= '$end' ORDER BY publishing_date DESC, publishing_time DESC";
		}
		else $query = "SELECT * FROM News WHERE id>0 ".$categories_query." ORDER BY publishing_date DESC, publishing_time DESC";
	} 
	else 
	{
		if((isset($_SESSION['start']))&&(isset($_SESSION['end']))&&(strlen($start)!=0)&&(strlen($end)!=0))
		{
			$query = "SELECT * FROM News WHERE id>0 AND  publishing_date >= '$start' AND publishing_date <= '$end' ORDER BY publishing_date DESC, publishing_time DESC";
		}
		elseif (isset($_SESSION['start'])&&(strlen($start)!=0))
		{
			$query = "SELECT * FROM News WHERE id>0 AND publishing_date >= '$start' ORDER BY publishing_date DESC, publishing_time DESC";
		}
		elseif (isset($_SESSION['end'])&&(strlen($end)!=0))
		{
			$query = "SELECT * FROM News WHERE id>0 AND publishing_date <= '$end' ORDER BY publishing_date DESC, publishing_time DESC";
		}
		else $query = $query = "SELECT * FROM News WHERE id>0 ORDER BY publishing_date DESC, publishing_time DESC";
	}
	
	$newLastID = 0;
	$nothere = 0;
	$rows = mysql_query($query) or die(mysql_error());
	// row contains ID title body owner publishing_date publishing_time category
	if(mysql_num_rows($rows))
	{
		while($row = mysql_fetch_row($rows))
		{
			if($newLastID==0)
			{
				$newLastID = $row[0];
			}
			if(($oldLastID<$newLastID)&&($nothere==0))
			{
				echo "new";
				$nothere = 1;
			}
		}
	}
?>
