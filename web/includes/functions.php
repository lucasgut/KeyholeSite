<?
session_start();
function db_connect(& $db) // used to connect to the database
{
	include("config.php");
	$db=mysql_connect($dbhost, $dbuser, $dbpass) or die ('Cannot connect to server' . mysql_error());
	mysql_select_db($dbname);
}
$db;
db_connect($db);

function strip_all($string) // strip input to the database for minimum protection against injection attacks
{
	$string = htmlentities($string);
	$string = strip_tags($string);
	$string = stripslashes($string);
	$string = addslashes($string);
	return $string;
}

function checkvaliddata($char) 
// verifica username password stuff like that impotriva mysql injection and stuff ... returneaza >> 1 << daca totul e OK, altfel 0
{
  	$salt = "aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789.@ ";
    $i = 0;
	$c = 0;
	while ($i <= 63)
	{
	    $tmp = substr($salt, $i, 1);
	    if($tmp==$char) $c=1;
	    $i++;
	}
	return $c;
}

function list_categories($mode)
{
	if(isset($_SESSION['categories'])) $categories = $_SESSION['categories'];
	
	$rows = mysql_query("SELECT name FROM Categories WHERE id>0 ORDER BY name ASC") or die(mysql_error());
	if($mode=="")
	{
		if(mysql_num_rows($rows))
		{
			while($row = mysql_fetch_row($rows))
			{
				if(isset($_SESSION['categories'])) 
					{
						if(sizeof($categories)>0)
						{
							if(array_key_exists($row[0],$categories)) echo "<li><a class=\"selected-aside\">".$row[0]."</a></li>";
								else echo "<li><a>".$row[0]."</a></li>";
						}
						else echo "<li><a>".$row[0]."</a></li>";
					}
				else echo "<li><a>".$row[0]."</a></li>";
			}
		}
		else echo "<li>no categories</li>";
	}
	else if($mode=="select-options")
	{
		$count = 0;
		if(mysql_num_rows($rows))
		{
			while($row = mysql_fetch_row($rows))
			{
				if($count==0) { echo "<option selected=\"selected\" value=\"".$row[0]."\">".$row[0]."</option>"; $count++; }
				else echo "<option value=\"".$row[0]."\">".$row[0]."</option>";
			}
		}
		else echo "<option value=\"none\">none</option>";
	}
}

function list_news()
{
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
	
	$newlastID = 0;
	
	$rows = mysql_query($query) or die(mysql_error());
	// row contains ID title body owner publishing_date publishing_time category
	if(mysql_num_rows($rows))
	{
		while($row = mysql_fetch_row($rows))
		{
			if($newlastID==0) 
			{
				$newlastID = $row[0];
				if(isset($_SESSION['lastID']))
				{ 
					$_SESSION['lastID'] = $newlastID;
					$lastID = $newlastID;
				}
				else
				{ 
					// no previous call
					$_SESSION['lastID'] = $lastID;
				}				
			}
			if(isset($_SESSION['loggedin'])) $admincontrols = "<span class=\"controls\"><a href=\"deletenews.php?id=".$row[0]."\" target=\"_self\" class=\"delete\"></a><a href=\"editform.php\" title=\"Modify\" target=\"_self\" class=\"edit\"></a><a href=\"imageform.php?news_id=".$row[0]."\" title=\"Add image\" target=\"_self\" class=\"edit\"></a></span>";
			echo "
				<article>
					".$admincontrols."
					<h1>".$row[1]."</h1>
					<details>Posted by <span>".$row[3]."</span> in <span class=\"cat\">".$row[6]."</span> on <span>".$row[4]."</span> at <span>".$row[5]."</span>.</details>
					<p>".$row[2]."</p>
					<p>
			";

					//Load images
					$imageQuery = "SELECT id FROM Images WHERE news_id = '$row[0]'";
					$imageRows = mysql_query($imageQuery) or die(mysql_error());
					if(mysql_num_rows($imageRows))
					{
						while($imageRow = mysql_fetch_row($imageRows))
						{ 
							echo ('<img src="loadimage.php?id='.$imageRow[0].'" width="500" />');
						}
					}
			echo "
					</p>
				</article>
			";

		}
	}
	else echo "<article class=\"no-hover\"><h1>No news into the system</h1></article>";
}

function list_new_news()
{
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
	$rows = mysql_query($query) or die(mysql_error());

	// row contains ID title body owner publishing_date publishing_time category
	if(mysql_num_rows($rows))
	{
		while($row = mysql_fetch_row($rows))
		{ 
			if($newLastID==0)
			{
				$newLastID = $row[0];
				$lastID = $newLastID;
				$_SESSION['lastID'] = $newLastID;
			}
			if(isset($_SESSION['loggedin'])) $admincontrols = "<span class=\"controls\"><a href=\"deletenews.php?id=".$row[0]."\" target=\"_self\" class=\"delete\"></a><a href=\"\" name=\"modal\" rel=\"editform\" title=\"".$row[0]."\" target=\"_self\" class=\"edit\"></a></span>";
			echo "
				<article>
					".$admincontrols."
					<h1>".$row[1]."</h1>
					<details>Posted by <span>".$row[3]."</span> in <span class=\"cat\">".$row[6]."</span> on <span>".$row[4]."</span> at <span>".$row[5]."</span>.</details>
					<p>".$row[2]."</p>
			";

					//Load images
					$imageQuery = "SELECT * FROM Images WHERE news_id = '$newLastID'";
					$imageRows = mysql_query($imageQuery) or die(mysql_error());
					if(mysql_num_rows($imageRows))
					{
						while($imageRow = mysql_fetch_row($imageRows))
						{ 
							header('Content-type: image/jpg');
						    echo $row['image'];
						}
					}
			echo "
				</article>
			";

		}
	}
	else echo "<article class=\"no-hover\"><h1>No news into the system</h1></article>";
}

function initiateLastID()
{
	if(!isset($_SESSION['lastID']))
	{ 
		// no previous call
		$query = "SELECT id FROM News WHERE id>0 ORDER BY id DESC LIMIT 1";
		$data = mysql_query($query) or die(mysql_error());
		$_SESSION['lastID'] = $data['id'];
		$lastID = $data['id'];
	}
}

?>
